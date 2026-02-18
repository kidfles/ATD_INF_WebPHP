<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

/**
 * VerifyEmailController
 * 
 * Beheert de afronding van het e-mailverificatieproces.
 * Controleert de authenticiteit van de verificatie-link en markeert de gebruiker als geverifieerd.
 */
class VerifyEmailController extends Controller
{
    /**
     * Markeer het e-mailadres van de geauthenticeerde gebruiker als geverifieerd.
     * 
     * @param EmailVerificationRequest $request Het gevalideerde verificatieverzoek.
     * @return \Illuminate\Http\RedirectResponse Redirect naar het dashboard met status.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // 1. Controleer of de gebruiker al geverifieerd is
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        // 2. Markeer de e-mail als geverifieerd en activeer het Verified event
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        // 3. Stuur de gebruiker door naar het dashboard met een bevestiging
        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
