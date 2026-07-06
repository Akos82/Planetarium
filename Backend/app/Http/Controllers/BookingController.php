<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmation;
use App\Models\Booking;
use App\Models\Show;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    /**
     * A foglalás oldal megjelenítése.
     * Admin felhasználónak az összes aktív foglalást adja át a nézetnek,
     * normál felhasználónak csak az aktív előadások listáját.
     */
    public function index()
    {
        $shows = Show::where('is_active', true)->orderBy('sort_order')->get();

        if (auth()->user()->is_admin) {
            // Admin nézet: aktív (pending/confirmed) foglalások dátum szerint rendezve
            $bookings = Booking::with('show')
                ->whereIn('status', ['pending', 'confirmed'])
                ->orderBy('booking_date')
                ->orderBy('booking_time')
                ->get();
            return view('booking', compact('shows', 'bookings'));
        }

        return view('booking', compact('shows'));
    }

    /**
     * Új foglalás rögzítése.
     *
     * Kettős foglalás megelőzése kétszintű védelemmel:
     *  1. Alkalmazásszintű ellenőrzés DB-tranzakción belül (race condition ellen)
     *  2. Adatbázis-szintű partial unique index (végső garancia)
     *
     * Sikeres foglalás után e-mail visszaigazolást küld a megadott e-mail címre.
     */
    public function store(Request $request)
    {
        // Bemeneti adatok validálása – csak érvényes adatok kerülhetnek az adatbázisba
        $data = $request->validate([
            'contact_name'      => 'required|string|max:255',
            'contact_email'     => 'required|email',
            'contact_phone'     => 'required|string|max:50',
            'group_name'        => 'required|string|max:255',
            'booking_date'      => 'required|date|after_or_equal:today',
            'booking_time'      => 'required|date_format:H:i',
            'group_size'        => 'required|integer|min:1|max:27',
            'with_presentation' => 'required|boolean',
            'show_id'           => 'nullable|exists:shows,id',
            'notes'             => 'nullable|string|max:1000',
        ]);

        // Normalizálás: az időpontot mindig HH:MM formátumban tároljuk,
        // mert a Filament adminfelületen HH:MM:SS formátumban kerülhet be az adatbázisba
        $bookingTime = substr($data['booking_time'], 0, 5);
        $data['booking_time'] = $bookingTime;

        $conflict = false;

        try {
            // DB-tranzakció az egyidejű (párhuzamos) kérések kezeléséhez.
            // Ha két felhasználó egyszerre próbálja lefoglalni ugyanazt az időpontot,
            // csak az egyikük fog sikerrel járni – a tranzakció zárolással gondoskodik erről.
            DB::transaction(function () use ($data, $bookingTime, &$conflict) {

                // Ellenőrizzük, hogy a választott dátum+időpontra van-e már aktív foglalás
                $exists = Booking::whereDate('booking_date', $data['booking_date'])
                    ->whereRaw("substr(booking_time, 1, 5) = ?", [$bookingTime])
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->exists();

                if ($exists) {
                    // Ütközés esetén referencia-változón keresztül jelzünk a külső hatókörnek
                    $conflict = true;
                    return;
                }

                // Szabad az időpont – foglalás létrehozása azonnal „confirmed" státusszal
                Booking::create(array_merge($data, ['status' => 'confirmed']));
            });
        } catch (\Throwable $e) {
            // Váratlan adatbázishiba (pl. az unique index constraint violation)
            return back()
                ->withErrors(['booking_time' => 'Hiba történt a foglalás rögzítése során. Kérjük, próbálja újra.'])
                ->withInput();
        }

        // Ha a tranzakcióban ütközést észleltünk, visszajelzünk a felhasználónak
        if ($conflict) {
            return back()
                ->withErrors(['booking_time' => 'Ez az időpont már foglalt! Válasszon másikat a naptárból.'])
                ->withInput();
        }

        // Az imént létrehozott foglalás lekérése az e-mail küldéshez
        $booking = Booking::whereDate('booking_date', $data['booking_date'])
            ->whereRaw("substr(booking_time, 1, 5) = ?", [$data['booking_time']])
            ->whereIn('status', ['pending', 'confirmed'])
            ->latest()
            ->first();

        // E-mail visszaigazolás küldése a megadott kapcsolattartói e-mail címre
        if ($booking) {
            Mail::to($booking->contact_email)->send(new BookingConfirmation($booking));
        }

        return redirect()->route('booking')
            ->with('success', 'Foglalása sikeresen beérkezett! Visszaigazolást küldtünk a megadott e-mail címre.');
    }

    /**
     * Foglalt időpontok JSON API-ja a naptárhoz.
     *
     * Személyes adatok minimalizálása (GDPR): a csoport neve (group_name)
     * csak adminisztrátor számára kerül visszaadásra. Normál felhasználónak
     * null értéket adunk vissza, így a naptárban csak az időpont és létszám látható.
     */
    public function bookedDates(): JsonResponse
    {
        $isAdmin = Auth::check() && Auth::user()->is_admin;

        $dates = Booking::whereIn('status', ['pending', 'confirmed'])
            ->where('group_size', '<=', 27)
            ->get(['booking_date', 'booking_time', 'group_name', 'group_size'])
            ->map(fn ($b) => [
                'date'       => $b->booking_date->format('Y-m-d'),
                'time'       => $b->booking_time,
                // PII minimalizálás: csak admin látja a csoport nevét
                'group'      => $isAdmin ? $b->group_name : null,
                'group_size' => $b->group_size,
            ]);

        return response()->json($dates);
    }
}
