<?php declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * PasswordController
 * 
 * Beheert het bijwerken van het wachtwoord voor ingelogde gebruikers.
 */
class PasswordController extends Controller
{
    /**
     * Werk het wachtwoord van de huidige gebruiker bij.
     * Controleert eerst het huidige wachtwoord voor de veiligheid.
     * 
     * @param Request $request Het huidige request object.
     * @return \Illuminate\Http\RedirectResponse Redirect terug met statusmelding.
     */
    public function update(Request $request): RedirectResponse
    {
        // 1. Valideer de wachtwoordvelden (huidig, nieuw en bevestiging)
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        // 2. Hash het nieuwe wachtwoord en werk de gebruiker bij
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // 3. Keer terug naar de profielpagina met een succesmelding
        return back()->with('status', 'password-updated');
    }
}
