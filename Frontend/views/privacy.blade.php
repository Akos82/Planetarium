@extends('layouts.app')

@section('title', __('privacy.page_title'))

@section('breadcrumb')
    {{ __('privacy.title') }}
@endsection

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('privacy.title') }}</h1>
    <p class="text-gray-500 text-sm mb-8">{{ __('privacy.effective') }}</p>

    <div class="prose prose-gray max-w-none space-y-8">

        <section>
            <h2 class="text-xl font-semibold text-gray-800 mb-3">1. Az adatkezelő adatai</h2>
            <p class="text-gray-600">
                <strong>Adatkezelő:</strong> Sapientia Erdélyi Magyar Tudományegyetem – Csíkszeredai Kar<br>
                <strong>Cím:</strong> Csíkszereda, Szabadság tér 1., 6. emelet<br>
                <strong>E-mail:</strong> planetarium@uni.sapientia.ro<br>
                <strong>Telefon:</strong> 0724 517 526
            </p>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-800 mb-3">2. Kezelt személyes adatok</h2>
            <p class="text-gray-600 mb-2">A webalkalmazás az alábbi személyes adatokat kezeli:</p>
            <ul class="list-disc list-inside text-gray-600 space-y-1">
                <li><strong>Regisztráció során:</strong> teljes név, e-mail cím, titkosított jelszó</li>
                <li><strong>Foglalás során:</strong> intézmény neve, kapcsolattartó neve, e-mail cím, telefonszám, csoportlétszám, látogatás dátuma és időpontja</li>
                <li><strong>Automatikusan:</strong> munkamenet adatok (IP cím, böngésző típusa)</li>
            </ul>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-800 mb-3">3. Az adatkezelés célja és jogalapja</h2>
            <p class="text-gray-600">
                Az adatok kezelésének célja a planetáriumi foglalások lebonyolítása, a látogatókkal való kapcsolattartás, valamint a rendszer biztonságos működésének biztosítása. Az adatkezelés jogalapja a felhasználó önkéntes hozzájárulása (GDPR 6. cikk (1) bekezdés a) pont).
            </p>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-800 mb-3">4. Az adatok megőrzési ideje</h2>
            <p class="text-gray-600">
                A foglalási adatokat a látogatás időpontját követő <strong>1 évig</strong> őrizzük meg, ezt követően törlésre kerülnek. A felhasználói fiók adatai a fiók törléséig kerülnek tárolásra.
            </p>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-800 mb-3">5. Az Ön jogai</h2>
            <p class="text-gray-600 mb-2">A GDPR értelmében Ön jogosult:</p>
            <ul class="list-disc list-inside text-gray-600 space-y-1">
                <li><strong>Hozzáférési jog:</strong> tájékoztatást kérni a kezelt adatairól</li>
                <li><strong>Helyesbítési jog:</strong> kérni a pontatlan adatok javítását</li>
                <li><strong>Törlési jog:</strong> kérni a személyes adatok törlését</li>
                <li><strong>Hordozhatósági jog:</strong> adatait géppel olvasható formátumban megkapni</li>
                <li><strong>Tiltakozáshoz való jog:</strong> tiltakozni az adatkezelés ellen</li>
            </ul>
            <p class="text-gray-600 mt-2">
                Jogai gyakorlásához kérjük vegye fel a kapcsolatot velünk a <a href="mailto:planetarium@uni.sapientia.ro" class="text-brand-600 hover:underline">planetarium@uni.sapientia.ro</a> e-mail címen, vagy fiókja törléséhez használja az alábbi lehetőséget.
            </p>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-800 mb-3">6. Sütik (cookie-k)</h2>
            <p class="text-gray-600">
                A weboldal kizárólag munkamenet-sütiket (session cookies) használ, amelyek a bejelentkezési állapot megőrzéséhez szükségesek. Ezek a sütik a böngésző bezárásakor automatikusan törlődnek. Harmadik fél követési sütiket nem alkalmazunk.
            </p>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-800 mb-3">7. Adatbiztonsági intézkedések</h2>
            <p class="text-gray-600">
                A jelszavak bcrypt algoritmussal titkosítva kerülnek tárolásra. A rendszer CSRF védelemmel és HTTPS kapcsolaton keresztüli adatátvitellel biztosítja az adatok védelmét.
            </p>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-800 mb-3">8. Jogorvoslat</h2>
            <p class="text-gray-600">
                Amennyiben úgy érzi, hogy adatkezelésünk sérti a GDPR rendelkezéseit, panaszt nyújthat be a román Személyes Adatok Védelméért Felelős Hatósághoz (ANSPDCP – <a href="https://www.dataprotection.ro" class="text-brand-600 hover:underline" target="_blank">www.dataprotection.ro</a>).
            </p>
        </section>

    </div>
</div>

@auth
<div class="max-w-3xl mx-auto px-4 pb-12">
    <div class="border-t border-gray-200 pt-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-3">{{ __('privacy.delete_title') }}</h2>
        <p class="text-gray-600 mb-4">
            {{ __('privacy.delete_desc') }}
        </p>
        <form method="POST" action="{{ route('account.delete') }}">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-2.5 rounded-lg transition text-sm"
                    onclick="return confirm('{{ __('privacy.delete_confirm') }}')">
                {{ __('privacy.delete_btn') }}
            </button>
        </form>
    </div>
</div>
@endauth
@endsection
