<?php declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCompanyProfileRequest;
use Illuminate\Http\Request;
use App\Models\PageComponent;
use App\Jobs\ProcessAdvertisementImport;
use App\Enums\ContractStatus;
use App\Enums\AdvertisementType;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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
    public function edit(Request $request): View
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
    public function update(UpdateCompanyProfileRequest $request): RedirectResponse
    {
        $company = $request->user()->companyProfile;

        // 1. Algemene instellingen bijwerken (Branding, URL, KVK)
        $data = $request->validated();
        
        // Data cleaning: Ensure value is null if policy is 'none' to prevent stale data
        if (($data['wear_and_tear_policy'] ?? '') === 'none') {
            $data['wear_and_tear_value'] = null;
        }

        // 1b. Security: Only allow return policy updates if contract is approved
        if ($company->contract_status !== ContractStatus::Approved) {
            unset($data['wear_and_tear_policy']);
            unset($data['wear_and_tear_value']);
        }

        $company->update($data);

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

        return back()->with('status', __('Settings and page saved successfully!'));
    }

    /**
     * Genereer een nieuwe API-token voor de bedrijfstoegang.
     * 
     * @param Request $request Het huidige HTTP request.
     * @return \Illuminate\Http\RedirectResponse Redirect terug met de nieuwe token (eenmalig zichtbaar).
     */
    public function generateToken(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Beveiligingscheck: Contract moet goedgekeurd zijn voor API-toegang
        if ($user->companyProfile->contract_status !== ContractStatus::Approved) {
            return back()->with('error', __('Approve your contract first to get API access.'));
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
     * Valideert het bestand en de inhoud vooraf. Alleen als alle rijen geldig zijn
     * wordt de achtergrond-job gestart. Anders krijgt de gebruiker direct feedback.
     * 
     * @param Request $request Het HTTP request met het CSV-bestand.
     * @return \Illuminate\Http\RedirectResponse Redirect terug met statusmelding.
     */
    public function importCsv(Request $request): RedirectResponse
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        // === PRE-VALIDATIE: CSV inhoud controleren voordat de job wordt gestart ===
        $file = $request->file('csv_file');
        $reader = \League\Csv\Reader::createFromPath($file->getPathname(), 'r');
        $reader->setHeaderOffset(0);

        // 1. Check: bevat het CSV de vereiste kolommen?
        $headers = $reader->getHeader();
        $requiredHeaders = ['title', 'description', 'price', 'type'];
        $missingHeaders = array_diff($requiredHeaders, $headers);

        if (!empty($missingHeaders)) {
            return back()->withErrors([
                'csv_file' => 'De CSV mist de volgende kolommen: ' . implode(', ', $missingHeaders) . '. Verwacht: title, description, price, type.',
            ]);
        }

        // 2. Check: zijn alle type-waarden geldig?
        $allowedTypes = array_column(AdvertisementType::cases(), 'value');
        $invalidRows = [];
        $csvTypeCounts = []; // Telt hoeveel ads per type in de CSV staan

        foreach ($reader->getRecords() as $index => $record) {
            $type = strtolower(trim($record['type'] ?? ''));

            if (empty($type)) {
                $invalidRows[] = "Rij {$index}: type is leeg";
            } elseif (!in_array($type, $allowedTypes)) {
                $invalidRows[] = "Rij {$index}: ongeldig type '{$record['type']}' (toegestaan: " . implode(', ', $allowedTypes) . ")";
            } else {
                $csvTypeCounts[$type] = ($csvTypeCounts[$type] ?? 0) + 1;
            }

            if (empty(trim($record['title'] ?? ''))) {
                $invalidRows[] = "Rij {$index}: titel is leeg";
            }

            if (empty(trim($record['price'] ?? '')) || !is_numeric($record['price'])) {
                $invalidRows[] = "Rij {$index}: prijs ontbreekt of is geen getal";
            }
        }

        // 3. Check: zou de import het maximum van 4 per type overschrijden?
        $userId = auth()->id();
        foreach ($csvTypeCounts as $type => $csvCount) {
            $existingCount = \App\Models\Advertisement::where('user_id', $userId)
                ->where('type', $type)
                ->count();
            $total = $existingCount + $csvCount;

            if ($total > 4) {
                $remaining = max(0, 4 - $existingCount);
                $invalidRows[] = "Type '{$type}': u heeft al {$existingCount} advertentie(s), de CSV bevat {$csvCount} extra. Maximaal {$remaining} kunnen nog worden toegevoegd.";
            }
        }

        if (!empty($invalidRows)) {
            return back()->withErrors([
                'csv_file' => 'Het CSV-bestand bevat fouten: ' . implode('; ', array_slice($invalidRows, 0, 5))
                    . (count($invalidRows) > 5 ? ' (en ' . (count($invalidRows) - 5) . ' meer)' : ''),
            ]);
        }

        // === VALIDATIE GESLAAGD: bestand opslaan en job starten ===
        $path = $file->store('imports', 'local');

        ProcessAdvertisementImport::dispatch(auth()->id(), $path);

        return back()->with('status', __('Your CSV file is being processed in the background. The advertisements will appear soon.'));
    }
}