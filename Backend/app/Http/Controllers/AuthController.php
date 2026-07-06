<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Bejelentkezés feldolgozása.
     * Sikeres hitelesítés után a munkamenet-azonosítót regeneráljuk
     * a munkamenet-rögzítéses (session fixation) támadások megelőzésére.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Az Auth::attempt bcrypt-tel összehasonlítja a jelszót a tárolt hash-sel.
        // A második paraméter a „Emlékezz rám" checkbox értéke (hosszú életű cookie).
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'A megadott e-mail cím vagy jelszó helytelen.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Regisztráció feldolgozása.
     *
     * GDPR-megfelelőség: az 'accepted_terms' mező kötelező checkbox,
     * amely igazolja, hogy a felhasználó elolvasta és elfogadta az
     * adatvédelmi tájékoztatót, és hozzájárult adatai kezeléséhez.
     * Az ['accepted'] szabály megköveteli, hogy az érték "true" / "1" / "on" / "yes" legyen.
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'unique:users'],
            'password'      => ['required', 'min:8', 'confirmed'],
            // GDPR hozzájárulás – kötelező checkbox elfogadás
            'accepted_terms'=> ['accepted'],
        ], [
            'accepted_terms.accepted' => 'Az adatvédelmi tájékoztató elfogadása kötelező.',
        ]);

        // A jelszó soha nem kerül plain text formátumban tárolásra – bcrypt hash-elés
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home');
    }

    /**
     * Elfelejtett jelszó – e-mail beviteli oldal.
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Jelszó-visszaállítási e-mail küldése.
     * A Password::sendResetLink() tokent generál, eltárolja a
     * password_reset_tokens táblában, majd elküldi az e-mailt.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Jelszó visszaállítása – új jelszó beviteli oldal.
     * A token és az e-mail cím az URL-ből érkezik.
     */
    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Új jelszó mentése.
     * A Password::reset() ellenőrzi a tokent, majd meghívja a callback-et.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Kijelentkezés: munkamenet törlése és CSRF token újragenerálása.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    /**
     * Fiók és személyes adatok törlése (GDPR törlési jog).
     *
     * A törlés előtt kijelentkeztetjük a felhasználót és érvénytelenítjük
     * a munkamenetet, hogy a soft-delete után ne maradjon érvényes session.
     * A User::delete() az összes kapcsolódó adatot is törli (cascade).
     */
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        // Kijelentkezés és munkamenet törlése a fiók eltávolítása előtt
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Felhasználói rekord törlése az adatbázisból
        $user->delete();

        return redirect()->route('login')
            ->with('status', 'Fiókja és személyes adatai sikeresen törlésre kerültek.');
    }
}
