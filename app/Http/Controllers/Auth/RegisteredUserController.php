<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

/**
 * RegisteredUserController
 * 
 * Verantwoordelijk voor het registreren van nieuwe gebruikers.
 * Behandelt zowel particuliere als zakelijke registraties, inclusief
 * de automatische setup van bedrijfsprofielen voor zakelijke adverteerders.
 */
class RegisteredUserController extends Controller
{
    /**
     * Toon het registratieformulier.
     * 
     * @return \Illuminate\View\View De registratieweergave.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Verwerk een inkomend registratieverzoek.
     * Maakt de gebruiker aan, stelt eventueel het bedrijfsprofiel in en logt de gebruiker in.
     * 
     * @param Request $request Het inkomende registratieverzoek.
     * @return \Illuminate\Http\RedirectResponse Redirect naar de startpagina na registratie.
     * @throws \Illuminate\Validation\ValidationException Als de validatie faalt.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Valideer de inkomende gegevens (inclusief rol-specifieke velden)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:user,private_ad,business_ad'],
            'company_name' => ['required_if:role,business_ad', 'nullable', 'string', 'max:255'],
            'kvk_number' => ['required_if:role,business_ad', 'nullable', 'digits:8'],
        ]);

        // 2. Maak de nieuwe gebruikersaccount aan
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // 3. Voor zakelijke adverteerders: Bedrijfsprofiel en standaard whitelabel-onderdelen aanmaken
        if ($request->role === 'business_ad') {
            $company = \App\Models\CompanyProfile::create([
                'user_id' => $user->id,
                'company_name' => $request->company_name,
                'kvk_number' => $request->kvk_number,
                // Genereer een unieke URL slug op basis van de bedrijfsnaam
                'custom_url_slug' => \Illuminate\Support\Str::slug($request->company_name) . '-' . rand(100,999),
                'brand_color' => '#000000', // Standaardkleur is zwart
            ]);

            // Voeg direct drie standaardonderdelen toe aan de whitelabel-pagina
            \App\Models\PageComponent::create([
                'company_id' => $company->id,
                'component_type' => 'hero',
                'order' => 1,
                'content' => ['title' => $request->company_name, 'subtitle' => 'Welkom!']
            ]);
            
            \App\Models\PageComponent::create([
                'component_type' => 'text',
                'order' => 2,
                'company_id' => $company->id,
                'content' => [
                    'heading' => 'Over Ons',
                    'body' => 'Wij zijn net begonnen! Meer informatie volgt snel.'
                ]
            ]);

            \App\Models\PageComponent::create([
                'component_type' => 'featured_ads',
                'order' => 3,
                'company_id' => $company->id,
                'content' => []
            ]);
        }

        // 4. Activeer het Registered event (voor bijv. verificatie-emails)
        event(new Registered($user));

        // 5. Log de nieuwe gebruiker direct in
        Auth::login($user);

        // 6. Redirect naar het dashboard
        return redirect(route('dashboard.index', absolute: false));
    }
}
