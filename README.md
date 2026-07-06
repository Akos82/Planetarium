# Planetárium – Sapientia EMTE

A Sapientia EMTE Csíkszeredai Karának planetáriuma számára készített foglaláskezelő webalkalmazás.

## Követelmények

- PHP 8.3+
- Composer
- Laragon (ajánlott) vagy XAMPP

## Telepítés és indítás

### 1. Függőségek telepítése

```bash
cd Backend
composer install
```

### 2. Környezeti fájl létrehozása

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Adatbázis létrehozása

```bash
php artisan migrate --seed
```

### 4. Szerver indítása

```bash
php artisan serve
```

A weboldal elérhető: [http://127.0.0.1:8000](http://127.0.0.1:8000)

Az admin panel elérhető: [http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin)

## Bejelentkezési adatok

Az admin fiók adatait a `.env` fájlban lehet beállítani a `migrate --seed` futtatása előtt:

```
ADMIN_EMAIL=admin@planetarium.hu
ADMIN_PASSWORD=valtozd_meg_ezt
```

Ha ezek nincsenek beállítva, az alapértelmezett e-mail `admin@planetarium.hu`. **A jelszót az első bejelentkezés után feltétlenül változtasd meg.**

## Technológiák

- Laravel 13
- Filament 4
- TailwindCSS v4
- SQLite
- Vite
