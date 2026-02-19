<?php declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use App\Enums\UserRole;
use App\Enums\ContractStatus;

class ContractController extends Controller
{
    /**
     * Genereer en download het contract als PDF.
     * 
     * @return \Illuminate\Http\Response
     */
    public function download(): Response
    {
        $user = auth()->user();
        
        // Controleer of de gebruiker een zakelijke adverteerder is
        abort_unless(
            $user->role === UserRole::BusinessSeller && $user->companyProfile !== null,
            403,
            'Alleen zakelijke gebruikers kunnen een contract downloaden.'
        );

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
    public function upload(Request $request): RedirectResponse
    {
        $request->validate([
            'contract_pdf' => 'required|mimes:pdf|max:10240', // Max 10MB
        ]);

        $user = auth()->user();
        
        abort_unless(
            $user->role === UserRole::BusinessSeller && $user->companyProfile !== null,
            403,
            'Alleen zakelijke gebruikers kunnen een contract uploaden.'
        );
        
        $company = $user->companyProfile;

        // Opslaan op de 'local' disk (beveiligd, niet direct via URL toegankelijk)
        $path = $request->file('contract_pdf')->store('contracts', 'local');

        // Werk profiel bij: status gaat naar 'pending' voor handmatige controle
        $company->update([
            'contract_file_path' => $path,
            'contract_status' => ContractStatus::Pending 
        ]);

        return back()->with('success', __('Contract successfully uploaded...'));
    }

    }
}
