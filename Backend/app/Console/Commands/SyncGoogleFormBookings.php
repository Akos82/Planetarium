<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

#[Signature('bookings:sync-google-forms')]
#[Description('Szinkronizálja a Google Forms foglalásokat az adatbázissal')]
class SyncGoogleFormBookings extends Command
{
    public function handle(): int
    {
        $spreadsheetId = config('services.google.spreadsheet_id');
        $apiKey = config('services.google.api_key');
        $range = config('services.google.sheet_range', 'Sheet1!A2:J');

        if (! $spreadsheetId || ! $apiKey) {
            $this->error('Google API kulcs vagy Spreadsheet ID nincs beállítva a .env fájlban.');
            return self::FAILURE;
        }

        $url = "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheetId}/values/{$range}";

        try {
            $response = Http::get($url, ['key' => $apiKey]);

            if ($response->failed()) {
                $this->error('Google Sheets API hiba: ' . $response->body());
                Log::error('Google Sheets sync failed', ['response' => $response->body()]);
                return self::FAILURE;
            }

            $rows = $response->json('values', []);
            $imported = 0;

            foreach ($rows as $index => $row) {
                // Oszlopok sorrendje: [timestamp, group_name, contact_name, email, phone, group_size, date, time, notes]
                $responseId = md5($row[0] ?? $index); // Google Forms timestamp mint egyedi azonosító

                if (Booking::where('google_form_response_id', $responseId)->exists()) {
                    continue;
                }

                Booking::create([
                    'google_form_response_id' => $responseId,
                    'group_name'    => $row[1] ?? 'Ismeretlen csoport',
                    'contact_name'  => $row[2] ?? '',
                    'contact_email' => $row[3] ?? '',
                    'contact_phone' => $row[4] ?? null,
                    'group_size'    => (int) ($row[5] ?? 1),
                    'booking_date'  => $this->parseDate($row[6] ?? ''),
                    'booking_time'  => $row[7] ?? '10:00',
                    'notes'         => $row[8] ?? null,
                    'status'        => 'pending',
                ]);

                $imported++;
            }

            $this->info("Szinkronizálás kész. {$imported} új foglalás importálva.");
            Log::info("Google Forms sync: {$imported} new bookings imported.");

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Hiba a szinkronizáció során: ' . $e->getMessage());
            Log::error('Google Forms sync exception', ['error' => $e->getMessage()]);
            return self::FAILURE;
        }
    }

    private function parseDate(string $dateString): string
    {
        // Google Forms dátumformátum: "2024.01.15" vagy "2024-01-15" vagy "01/15/2024"
        try {
            return \Carbon\Carbon::parse($dateString)->format('Y-m-d');
        } catch (\Exception) {
            return now()->format('Y-m-d');
        }
    }
}
