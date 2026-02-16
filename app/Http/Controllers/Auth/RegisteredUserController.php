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

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:user,private_ad,business_ad'],
            'company_name' => ['required_if:role,business_ad', 'nullable', 'string', 'max:255'],
            'kvk_number' => ['required_if:role,business_ad', 'nullable', 'digits:8'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($request->role === 'business_ad') {
            $company = \App\Models\CompanyProfile::create([
                'user_id' => $user->id,
                'company_name' => $request->company_name,
                'kvk_number' => $request->kvk_number,
                // Defaults
                'custom_url_slug' => \Illuminate\Support\Str::slug($request->company_name) . '-' . rand(100,999),
                'brand_color' => '#000000', // Default color
            ]);

            // Direct defaults aanmaken
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

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard.index', absolute: false));
    }
}
