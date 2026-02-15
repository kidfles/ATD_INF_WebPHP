<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompanySettingsController extends Controller
{
    public function edit(Request $request)
    {
        // Get the authenticated user's company profile
        $company = $request->user()->companyProfile;
        
        // If they don't have one (e.g., they are a regular user), redirect or error
        if (!$company) {
             abort(403, 'You do not have a company profile.');
        }

        return view('pages.dashboard.company.edit', compact('company'));
    }

    public function update(Request $request)
    {
        $company = $request->user()->companyProfile;

        $validated = $request->validate([
            'kvk_number' => ['required', 'digits:8'],
            'brand_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'custom_url_slug' => [
                'required', 
                'alpha_dash', 
                Rule::unique('company_profiles')->ignore($company->id)
            ],
        ]);

        $company->update($validated);

        return back()->with('status', 'Company settings updated!');
    }
}
