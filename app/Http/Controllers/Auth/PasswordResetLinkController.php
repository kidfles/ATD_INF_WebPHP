<?php declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

/**
 * PasswordResetLinkController
 * 
 * Beheert de verzoeken voor het versturen van wachtwoord-reset-links naar gebruikers
 * die hun wachtwoord zijn vergeten.
 */
class PasswordResetLinkController extends Controller
{
    /**
     * Toon de weergave voor het aanvragen van een wachtwoord-reset-link.
     * 
     * @return \Illuminate\View\View De weergave voor 'wachtwoord vergeten'.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Verwerk een inkomende aanvraag voor een wachtwoord-reset-link.
     * Verifieert het e-mailadres en verstuurt de link via de Password broker.
     * 
     * @param Request $request Het huidige request object met het e-mailadres.
     * @return \Illuminate\Http\RedirectResponse Redirect met status- of foutmelding.
     * @throws \Illuminate\Validation\ValidationException Als de validatie faalt.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Valideer het opgegeven e-mailadres
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // 2. Probeer de wachtwoord-reset-link te versturen via de ingebouwde broker
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // 3. Geef feedback op basis van het resultaat (succes of fout)
        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
