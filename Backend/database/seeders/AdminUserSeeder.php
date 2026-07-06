<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Admin felhasználó létrehozása vagy frissítése.
     *
     * A hitelesítő adatok a .env fájlból olvasódnak be, így azok
     * nem kerülnek be a verziókövetőbe (git). Ha a .env-ben nincs
     * megadva érték, az alapértelmezett értékek lépnek életbe.
     *
     * Az updateOrCreate() idempotens: többszöri futtatás esetén sem
     * hoz létre duplikált admin fiókot, csak frissíti a meglévőt.
     */
    public function run(): void
    {
        // Hitelesítő adatok betöltése a .env fájlból (GDPR + biztonsági megfontolás)
        $email    = env('ADMIN_EMAIL', 'admin@planetarium.hu');
        $password = env('ADMIN_PASSWORD', 'planetarium2025');

        User::updateOrCreate(
            // Kereső feltétel: e-mail cím alapján
            ['email' => $email],
            // Létrehozandó / frissítendő adatok
            [
                'name'     => 'Admin',
                'password' => Hash::make($password), // bcrypt hash-elés
                'is_admin' => true,
            ]
        );

        $this->command->info("Admin felhasználó létrehozva: {$email}");
    }
}
