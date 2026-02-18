<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * AuthenticatedSessionController
 * 
 * Beheert de authenticatie-sessies van de gebruiker.
 * Verantwoordelijk voor het tonen van het inlogformulier, het verifiëren
 * van inloggegevens en het beëindigen van de sessie.
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Toon het inlogformulier.
     * 
     * @return \Illuminate\View\View De inlogweergave.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Verwerk een inkomend authenticatieverzoek (inloggen).
     * Controleert de inloggegevens, regenereert de sessie en stuurt de gebruiker door.
     * 
     * @param LoginRequest $request Het gevalideerde inlogverzoek.
     * @return \Illuminate\Http\RedirectResponse Redirect naar de bedoelde pagina.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Authenticeer de gebruiker op basis van de verstrekte gegevens
        $request->authenticate();

        // 2. Regenereer het sessie-ID om session fixation aanvallen te voorkomen
        $request->session()->regenerate();

        // 3. Stuur de gebruiker door naar de bedoelde URL of het dashboard
        return redirect()->intended(route('dashboard.index', absolute: false));
    }

    /**
     * Beëindig de sessie van de gebruiker (uitloggen).
     * 
     * @param Request $request Het huidige request object.
     * @return \Illuminate\Http\RedirectResponse Redirect naar de homepagina.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // 1. Log de gebruiker uit bij de web-guard
        Auth::guard('web')->logout();

        // 2. Maak de huidige sessie ongeldig
        $request->session()->invalidate();

        // 3. Regenereer de CSRF-token voor de veiligheid
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
