<?php declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * EmailVerificationPromptController
 * 
 * Verantwoordelijk voor het tonen van de prompt die gebruikers vraagt
 * om hun e-mailadres te verifiëren voordat ze verder kunnen.
 */
class EmailVerificationPromptController extends Controller
{
    /**
     * Toon de melding voor e-mailverificatie.
     * Stuurt de gebruiker door naar het dashboard als de e-mail al geverifieerd is.
     * 
     * @param Request $request Het huidige request object.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        // 1. Controleer of de gebruiker al geverifieerd is
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('dashboard', absolute: false))
                    : view('auth.verify-email'); // 2. Zo niet, toon de verificatie-prompt
    }
}
