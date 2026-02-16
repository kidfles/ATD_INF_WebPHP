<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCompanyProfileRequest;
use Illuminate\Http\Request;
use App\Models\PageComponent;
use App\Jobs\ProcessAdvertisementImport;

/**
 * CompanySettingsController
 * 
 * Beheert de instellingen van het bedrijfsprofiel en de whitelabel pagina's.
 * Bevat logica voor het bewerken van merk-instellingen, paginacomponenten, API-toegang
 * en het bulk-importeren van advertenties via CSV.
 */
class CompanySettingsController extends Controller
{
    /**
     * Toon het formulier om de bedrijfsinstellingen te bewerken.
     * 
     * @param Request $request Het huidige HTTP request.
     * @return \Illuminate\View\View De weergave met het instellingenformulier.
     */
    public function edit(Request $request)
    {
        $company = $request->user()->companyProfile;
        if (!$company) {
             abort(403, 'U heeft geen bedrijfsprofiel.');
        }

        return view('pages.dashboard.company.edit', compact('company'));
    }

    /**
     * Werk de bedrijfsinstellingen en de whitelabel pagina-indeling bij.
     * 
     * @param UpdateCompanyProfileRequest $request Het gevalideerde request met instellingen en componenten.
     * @return \Illuminate\Http\RedirectResponse Redirect terug met succesmelding.
     */
    public function update(UpdateCompanyProfileRequest $request)
    {
        $company = $request->user()->companyProfile;

        // 1. Algemene instellingen bijwerken (Branding, URL, KVK)
        $company->update($request->validated());

        // 2. Pagina-componenten bijwerken (Hero teksten, Body teksten, etc.)
        if ($request->has('components')) {
            foreach ($request->input('components') as $id => $data) {
                $component = PageComponent::find($id);
                
                // Beveiligingscheck: zorg dat de component bij dit bedrijf hoort
                if ($component && $component->company_id === $company->id && isset($data['content'])) {
                    $component->update([
                        'content' => $data['content']
                    ]);
                }
            }
        }

        // 3. Volgorde van componenten bijwerken (indien gesorteerd in de UI)
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

    /**
     * Genereer een nieuwe API-token voor de bedrijfstoegang.
     * 
     * @param Request $request Het huidige HTTP request.
     * @return \Illuminate\Http\RedirectResponse Redirect terug met de nieuwe token (eenmalig zichtbaar).
     */
    public function generateToken(Request $request)
    {
        $user = $request->user();

        // Beveiligingscheck: Contract moet goedgekeurd zijn voor API-toegang
        if ($user->companyProfile->contract_status !== 'approved') {
            return back()->with('error', 'Keur eerst uw contract goed om API toegang te krijgen.');
        }

        // Verwijder oude tokens voor de veiligheid (slechts één actieve token per keer)
        $user->tokens()->delete();

        // Nieuwe token aanmaken
        $token = $user->createToken('company-api')->plainTextToken;

        // Token tijdelijk in de sessie opslaan zodat deze één keer getoond kan worden
        return back()->with('api_token', $token);
    }

    /**
     * Verwerk een CSV-upload met advertenties en start de achtergrond-import.
     * 
     * @param Request $request Het HTTP request met het CSV-bestand.
     * @return \Illuminate\Http\RedirectResponse Redirect terug met statusmelding.
     */
    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        // Bestand opslaan in private storage (niet publiek toegankelijk)
        $path = $request->file('csv_file')->store('imports', 'local');

        // Job dispatchen voor verwerking op de achtergrond
        ProcessAdvertisementImport::dispatch(auth()->id(), $path);

        return back()->with('status', 'Uw CSV-bestand wordt op de achtergrond verwerkt. De advertenties verschijnen binnenkort.');
    }
}