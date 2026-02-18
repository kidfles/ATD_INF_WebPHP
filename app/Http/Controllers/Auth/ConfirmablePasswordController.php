<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * ConfirmablePasswordController
 * 
 * Verantwoordelijk voor het bevestigen van het wachtwoord van de gebruiker
 * voordat gevoelige acties kunnen worden uitgevoerd.
 */
class ConfirmablePasswordController extends Controller
{
    /**
     * Toon de weergave voor wachtwoordbevestiging.
     * 
     * @return \Illuminate\View\View De bevestigingsweergave.
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * Bevestig het wachtwoord van de gebruiker.
     * Controleert of het ingevoerde wachtwoord overeenkomt met het wachtwoord van de ingelogde gebruiker.
     * 
     * @param Request $request Het huidige request object.
     * @return \Illuminate\Http\RedirectResponse Redirect naar de bedoelde actie.
     * @throws \Illuminate\Validation\ValidationException Als het wachtwoord onjuist is.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Valideer het ingevoerde wachtwoord tegen de huidige gebruiker
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        // 2. Sla het tijdstip van bevestiging op in de sessie
        $request->session()->put('auth.password_confirmed_at', time());

        // 3. Stuur de gebruiker door naar de oorspronkelijke bestemming
        return redirect()->intended(route('dashboard', absolute: false));
    }
}
