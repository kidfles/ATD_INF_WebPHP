<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCompanyProfileRequest;
use Illuminate\Http\Request;
use App\Models\PageComponent;

class CompanySettingsController extends Controller
{
    public function edit(Request $request)
    {
        $company = $request->user()->companyProfile;
        if (!$company) {
             abort(403, 'You do not have a company profile.');
        }

        return view('pages.dashboard.company.edit', compact('company'));
    }

    public function update(UpdateCompanyProfileRequest $request)
    {
        $company = $request->user()->companyProfile;

        // 1. Update Profile Settings (Branding, URL, KVK)
        $company->update($request->validated());

        // 2. Update Page Components (Hero texts, Body texts, etc.)
        if ($request->has('components')) {
            foreach ($request->input('components') as $id => $data) {
                $component = PageComponent::find($id);
                
                // Security check: ensure this component belongs to the user's company
                if ($component && $component->company_id === $company->id && isset($data['content'])) {
                    $component->update([
                        'content' => $data['content']
                    ]);
                }
            }
        }

        // 3. Update Order (if sorting was changed)
        if ($request->has('ordered_ids')) {
            foreach ($request->input('ordered_ids') as $order => $id) {
                $component = PageComponent::find($id);
                if ($component && $component->company_id === $company->id) {
                    $component->update(['order' => $order + 1]);
                }
            }
        }

        return back()->with('status', 'Instellingen en pagina succesvol opgeslagen!');
    }
}