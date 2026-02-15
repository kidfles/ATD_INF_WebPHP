<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCompanyProfileRequest;
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

    public function update(UpdateCompanyProfileRequest $request)
    {
        $company = $request->user()->companyProfile;

        // Authorization check is now handled in the Form Request's authorize() method
        // but explicit check here is also fine for double safety if desired,
        // though request authorization happens before controller method execution.
        
        $company->update($request->validated());

        return back()->with('status', 'Company settings updated!');
    }
}
