<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * EmailVerificationNotificationController
 * 
 * Verantwoordelijk voor het versturen van nieuwe e-mailverificatie instructies.
 * Wordt gebruikt wanneer de gebruiker de e-mail opnieuw wil aanvragen.
 */
class EmailVerificationNotificationController extends Controller
{
    /**
     * Verstuur een nieuwe e-mailverificatie notificatie.
     * Controleert eerst of de gebruiker niet al geverifieerd is.
     * 
     * @param Request $request Het huidige request object.
     * @return \Illuminate\Http\RedirectResponse Redirect terug of naar het dashboard.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Als de e-mail al geverifieerd is, stuur direct door
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // 2. Verstuur de verificatie e-mail naar de gebruiker
        $request->user()->sendEmailVerificationNotification();

        // 3. Keer terug met een succesmelding
        return back()->with('status', 'verification-link-sent');
    }
}
