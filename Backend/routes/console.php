<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Google Forms szinkronizáció 15 percenként
Schedule::command('bookings:sync-google-forms')->everyFifteenMinutes();

// Lejárt foglalások lezárása minden nap éjfélkor
Schedule::command('bookings:complete-expired')->dailyAt('00:05');
