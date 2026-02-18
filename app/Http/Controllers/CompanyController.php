<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\PageComponent;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

/**
 * CompanyController
 * 
 * Beheert de publieke bedrijfspagina's (whitelabeling) en de contractafhandeling 
 * voor zakelijke adverteerders.
 */
class CompanyController extends Controller
{
    /**
     * Toon de publieke profielpagina van een bedrijf.
     * Maakt automatisch standaardcomponenten aan als deze nog niet bestaan.
     * 
     * @param CompanyProfile $company Het bedrijfsprofiel dat getoond moet worden.
     * @return \Illuminate\View\View De whitelabel weergave.
     */
    public function show(CompanyProfile $company)
    {
        // 2. Laad de data (nu gegarandeerd aanwezig via Observer)
        // We laden ook de reviews van de USER die bij dit bedrijf hoort.
        $company->load(['user.advertisements' => function ($query) {
            $query->latest()->limit(3); // Fix N+1 en beperk het aantal advertenties
        }, 'user.reviewsReceived.reviewer', 'pageComponents' => function ($query) {
            $query->orderBy('order');
        }]);

        // Bereken stats (op basis van de User, want daar hangen de reviews aan)
        $user = $company->user;
        $averageRating = $user->reviewsReceived->avg('rating') ?? 0;
        $reviewCount = $user->reviewsReceived->count();
        
        return view('pages.whitelabel.show', compact('company', 'averageRating', 'reviewCount', 'user'));
    }

    /**
     * Genereer en download het contract als PDF.
     * 
     * @return \Illuminate\Http\Response De PDF-download.
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException Als de gebruiker geen zakelijke adverteerder is.
     */
    public function downloadContract()
    {
        $user = auth()->user();
        
        // Controleer of de gebruiker een zakelijke adverteerder is
        if ($user->role !== 'business_ad' || !$user->companyProfile) {
            abort(403, 'Alleen zakelijke gebruikers kunnen een contract downloaden.');
        }

        $company = $user->companyProfile;
        
        // Genereer PDF op basis van een Blade template
        $pdf = Pdf::loadView('pdf.contract', compact('company', 'user'));
        
        return $pdf->download('contract-' . $company->kvk_number . '.pdf');
    }

    /**
     * Upload een ondertekend contract (PDF).
     * 
     * @param Request $request Het HTTP request met het PDF bestand.
     * @return \Illuminate\Http\RedirectResponse Redirect terug met succesmelding.
     */
    public function uploadContract(Request $request)
    {
        $request->validate([
            'contract_pdf' => 'required|mimes:pdf|max:10240', // Max 10MB
        ]);

        $user = auth()->user();
        
        if ($user->role !== 'business_ad' || !$user->companyProfile) {
            abort(403, 'Alleen zakelijke gebruikers kunnen een contract uploaden.');
        }
        
        $company = $user->companyProfile;

        // Opslaan op de 'local' disk (beveiligd, niet direct via URL toegankelijk)
        $path = $request->file('contract_pdf')->store('contracts', 'local');

        // Werk profiel bij: status gaat naar 'pending' voor handmatige controle
        $company->update([
            'contract_file_path' => $path,
            'contract_status' => 'pending' 
        ]);

        return back()->with('success', __('Contract successfully uploaded...'));
    }

    /**
     * TEST ONLY: Keur het contract direct goed voor ontwikkelings-/testdoeleinden.
     * 
     * @return \Illuminate\Http\RedirectResponse Redirect terug met statusmelding.
     */
    public function approveContractTest()
    {
        $user = auth()->user();
        
        if ($user->role !== 'business_ad' || !$user->companyProfile) {
            abort(403, 'Alleen zakelijke gebruikers kunnen deze actie uitvoeren.');
        }
        
        $company = $user->companyProfile;
        
        // Keur het contract direct goed
        $company->update([
            'contract_status' => 'approved'
        ]);

        return back()->with('status', __('Contract is now approved!'));
    }
}
