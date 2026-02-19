<?php declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

/**
 * NewPasswordController
 * 
 * Beheert het proces van het instellen van een nieuw wachtwoord
 * nadat een gebruiker een geldig wachtwoord-reset-token heeft verstrekt.
 */
class NewPasswordController extends Controller
{
    /**
     * Toon het wachtwoord-reset formulier.
     * 
     * @param Request $request Het huidige request object inclusief de token.
     * @return \Illuminate\View\View De weergave voor het instellen van een nieuw wachtwoord.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Verwerk een verzoek om een nieuw wachtwoord in te stellen.
     * Valideert de gegevens en werkt het wachtwoord van de gebruiker bij.
     * 
     * @param Request $request Het request met de token en het nieuwe wachtwoord.
     * @return \Illuminate\Http\RedirectResponse Redirect met statusmelding.
     * @throws \Illuminate\Validation\ValidationException Als de validatie faalt.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Valideer de inkomende gegevens (token, e-mail en nieuw wachtwoord)
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. Probeer het wachtwoord te resetten via de Password broker
        // Als dit lukt, wordt de callback uitgevoerd om het model bij te werken.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                // Wachtwoord hashen en opslaan
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                // Activeer het PasswordReset event
                event(new PasswordReset($user));
            }
        );

        // 3. Geef feedback aan de gebruiker op basis van het resultaat
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
