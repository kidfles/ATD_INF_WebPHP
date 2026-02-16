<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * ProfileController
 * 
 * Beheert de persoonlijke instellingen van de gebruiker, inclusief het bijwerken 
 * van profielinformatie en het verwijderen van accounts.
 */
class ProfileController extends Controller
{
    /**
     * Toon het formulier voor het bewerken van het profiel.
     * 
     * @param Request $request Het huidige HTTP request.
     * @return View De weergave met het bewerkingsformulier.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Werk de profielinformatie van de gebruiker bij.
     * 
     * @param ProfileUpdateRequest $request Het gevalideerde request met profielgegevens.
     * @return RedirectResponse Redirect naar het formulier met statusmelding.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        // Als het e-mailadres is gewijzigd, moet de verificatie opnieuw plaatsvinden
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Verwijder het account van de gebruiker.
     * 
     * @param Request $request Het HTTP request voor validatie en sessiebeheer.
     * @return RedirectResponse Redirect naar de startpagina na verwijdering.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Uitloggen voordat het account wordt verwijderd
        Auth::logout();

        $user->delete();

        // Sessie ongeldig maken en CSRF token regenereren voor veiligheid
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
