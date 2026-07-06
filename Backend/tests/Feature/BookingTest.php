<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Foglalási rendszer funkcionális tesztjei.
 *
 * A RefreshDatabase trait minden egyes teszt előtt visszaállítja
 * az adatbázist, így a tesztek egymástól függetlenek és megismételhetők.
 * SQLite in-memory adatbázist használ, ezért gyors és nem igényel külső adatbázist.
 */
class BookingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. teszteset: Kettős foglalás megakadályozása
     *
     * Ellenőrzi, hogy a rendszer nem enged két aktív foglalást
     * ugyanarra a dátum+időpont kombinációra.
     * Elvárás: a második foglalási kísérlet 'booking_time' hibával tér vissza,
     * és az adatbázisban csak 1 foglalás marad.
     */
    public function test_double_booking_is_prevented(): void
    {
        $user = User::factory()->create();

        // Első foglalás közvetlen létrehozása (a controller megkerülésével)
        Booking::create([
            'group_name'        => 'Teszt Iskola',
            'contact_name'      => 'Kovács János',
            'contact_email'     => 'kovacs@teszt.hu',
            'contact_phone'     => '0700000000',
            'group_size'        => 10,
            'booking_date'      => '2027-08-01',
            'booking_time'      => '10:00',
            'with_presentation' => true,
            'status'            => 'confirmed',
        ]);

        // Második foglalási kísérlet ugyanarra az időpontra
        $response = $this->actingAs($user)->post(route('booking.store'), [
            'group_name'        => 'Másik Iskola',
            'contact_name'      => 'Nagy Éva',
            'contact_email'     => 'nagy@teszt.hu',
            'contact_phone'     => '0711111111',
            'group_size'        => 5,
            'booking_date'      => '2027-08-01',
            'booking_time'      => '10:00',
            'with_presentation' => false,
        ]);

        // Elvárások: hibaüzenet jelenik meg, és nem jött létre új foglalás
        $response->assertSessionHasErrors('booking_time');
        $this->assertEquals(1, Booking::count());
    }

    /**
     * 2. teszteset: Hozzáférés-védelem – bejelentkezés nélküli foglalás tiltása
     *
     * Ellenőrzi, hogy bejelentkezetlen látogató a foglalási oldalra navigálva
     * automatikusan a bejelentkezési oldalra kerül-e (HTTP 302 redirect).
     */
    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get(route('booking'));

        $response->assertRedirect(route('login'));
    }

    /**
     * 3. teszteset: Szerepkör alapú nézet – admin és felhasználó eltérő tartalmat lát
     *
     * Ellenőrzi, hogy az admin felhasználónak a „Beérkezett foglalások" lista jelenik meg,
     * míg a normál felhasználónak a foglalási űrlap. Ez biztosítja, hogy a szerepkörök
     * helyes tartalmat kapnak.
     */
    public function test_admin_sees_booking_list_not_form(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user  = User::factory()->create(['is_admin' => false]);

        // Admin: a foglalások listájának fejlécét várjuk
        $adminResponse = $this->actingAs($admin)->get(route('booking'));
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('Beérkezett foglalások', false);

        // Normál felhasználó: a foglalási form jelenik meg
        $userResponse = $this->actingAs($user)->get(route('booking'));
        $userResponse->assertStatus(200);
        $userResponse->assertSee('Foglalás', false);
    }

    /**
     * 4. teszteset: Automatikus státuszváltás – lejárt foglalások lezárása
     *
     * Ellenőrzi, hogy a bookings:complete-expired artisan parancs
     * a múltbeli (már lezajlott) foglalásokat 'completed' státuszra állítja.
     * Ez fontos az aktív foglalások naptárból való eltüntetéséhez.
     */
    public function test_expired_bookings_are_marked_completed(): void
    {
        // Múltbeli foglalás létrehozása 'confirmed' státusszal
        Booking::create([
            'group_name'        => 'Régi Iskola',
            'contact_name'      => 'Régi Kapcsolat',
            'contact_email'     => 'regi@teszt.hu',
            'contact_phone'     => '0722222222',
            'group_size'        => 15,
            'booking_date'      => '2020-01-01',
            'booking_time'      => '10:00',
            'with_presentation' => false,
            'status'            => 'confirmed',
        ]);

        // Az artisan parancs futtatása
        $this->artisan('bookings:complete-expired');

        // Elvárás: a foglalás státusza 'completed'-re változott
        $this->assertEquals('completed', Booking::first()->status);
    }
}
