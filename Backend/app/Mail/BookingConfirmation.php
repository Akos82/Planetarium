<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Foglalás-visszaigazoló e-mail osztály.
 *
 * Laravel Mailable: a framework automatikusan példányosítja és elküldi
 * a Mail::to()->send() híváskor. A $booking property public láthatósága
 * szükséges, hogy a Blade e-mail sablonban közvetlenül hozzáférhető legyen.
 *
 * Fejlesztői környezetben az e-mailek nem kerülnek ténylegesen elküldésre –
 * a MAIL_MAILER=log beállítással a storage/logs/laravel.log fájlba íródnak.
 */
class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param Booking $booking A visszaigazolandó foglalás modellje.
     *                         A public láthatóság teszi elérhetővé a Blade sablonban.
     */
    public function __construct(public Booking $booking) {}

    /**
     * Az e-mail borítékja (tárgy és feladó).
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Foglalás visszaigazolása – Planetárium',
        );
    }

    /**
     * Az e-mail tartalma – a resources/views/emails/booking-confirmation.blade.php sablont használja.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-confirmation',
        );
    }
}
