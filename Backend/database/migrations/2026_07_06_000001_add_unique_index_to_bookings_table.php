<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Adatbázis-szintű kettős foglalás-védelem partial unique index segítségével.
     *
     * Miért partial (feltételes) index?
     * A törölt vagy lejárt ('completed', 'cancelled') foglalások nem akadályozzák meg,
     * hogy ugyanarra az időpontra új foglalás érkezzen. Az index CSAK az aktív
     * ('pending', 'confirmed') rekordokra érvényes.
     *
     * Ez a védelem az alkalmazásszintű DB::transaction() ellenőrzés mellé
     * egy második biztonsági réteget képez: még ha az alkalmazás logika
     * megkerülhető lenne, az adatbázis szinten is garantált az egyediség.
     *
     * Megjegyzés: a WHERE záradékos (partial) index SQLite-specifikus szintaxis.
     */
    public function up(): void
    {
        DB::statement('
            CREATE UNIQUE INDEX IF NOT EXISTS bookings_active_slot_unique
            ON bookings (booking_date, booking_time)
            WHERE status IN (\'pending\', \'confirmed\')
        ');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS bookings_active_slot_unique');
    }
};
