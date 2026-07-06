<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ShowController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Hitelesítési útvonalak
|--------------------------------------------------------------------------
| A 'guest' middleware biztosítja, hogy bejelentkezett felhasználó
| ne férhessen hozzá a bejelentkezési/regisztrációs oldalakhoz.
*/
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Publikus útvonalak
|--------------------------------------------------------------------------
| Ezek az oldalak bejelentkezés nélkül is elérhetők.
| Az adatvédelmi tájékoztató szándékosan publikus: a GDPR előírja,
| hogy az adatkezelési tájékoztató mindenki számára hozzáférhető legyen.
*/
Route::get('/', fn() => view('home'))->name('home');
Route::get('/rolunk', fn() => redirect()->route('home'))->name('about');
Route::get('/elerhetosegek', fn() => view('contact'))->name('contact');
Route::get('/eloadasok', [ShowController::class, 'index'])->name('shows');
Route::get('/adatvedelem', fn() => view('privacy'))->name('privacy');

/*
|--------------------------------------------------------------------------
| Védett útvonalak
|--------------------------------------------------------------------------
| Az 'auth' middleware megköveteli a bejelentkezést.
| Bejelentkezetlen felhasználó automatikusan a /login oldalra kerül.
| A foglalás és az API végpont szándékosan védett: a naptárban szereplő
| időpontok személyes adatnak minősülnek (GDPR).
*/
Route::middleware('auth')->group(function () {
    Route::get('/foglalas',  [BookingController::class, 'index'])->name('booking');
    Route::post('/foglalas', [BookingController::class, 'store'])->name('booking.store');
});

// Fiók törlése – GDPR törlési jog megvalósítása (DELETE metódus szükséges)
Route::delete('/account', [AuthController::class, 'deleteAccount'])
    ->name('account.delete')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| Nyelváltó
|--------------------------------------------------------------------------
| A kiválasztott nyelvet munkamenetbe (session) menti.
| A SetLocale middleware minden kérésnél beolvassa ezt az értéket.
*/
Route::get('/language/{locale}', function (string $locale) {
    if (in_array($locale, ['hu', 'ro'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back()->withHeaders(['Cache-Control' => 'no-store']);
})->name('language.switch');

/*
|--------------------------------------------------------------------------
| Naptár API
|--------------------------------------------------------------------------
| A bejelentkezett felhasználónak ad vissza foglalt időpontokat JSON-ban.
| A visszaadott adatok mennyisége szerepkörtől függ (PII minimalizálás).
*/
Route::get('/api/foglalt-datumok', [BookingController::class, 'bookedDates'])
    ->name('api.booked-dates')
    ->middleware('auth');
