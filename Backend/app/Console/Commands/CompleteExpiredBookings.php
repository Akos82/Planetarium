<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('bookings:complete-expired')]
#[Description('Lejárt foglalásokat lezárttá állítja')]
class CompleteExpiredBookings extends Command
{
    public function handle(): void
    {
        $count = Booking::whereIn('status', ['pending', 'confirmed'])
            ->whereDate('booking_date', '<', today())
            ->update(['status' => 'completed']);

        $this->info("$count foglalás lett lezárva.");
    }
}
