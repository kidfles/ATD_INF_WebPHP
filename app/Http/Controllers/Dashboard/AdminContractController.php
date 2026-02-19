<?php declare(strict_types=1);
 
namespace App\Http\Controllers\Dashboard;
 
use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use App\Enums\ContractStatus;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
 
/**
 * AdminContractController
 * 
 * Beheert de contracten voor admins. Hiermee kunnen admins
 * geüploade contracten inzien, downloaden, goedkeuren of afwijzen.
 */
class AdminContractController extends Controller
{
    /**
     * Toon een overzicht van alle contracten die wachten op goedkeuring.
     * 
     * @return View De overzichtspagina.
     */
    public function index(): View
    {
        // Alleen de contracten ophalen die de status 'pending' hebben
        $pendingProfiles = CompanyProfile::where('contract_status', ContractStatus::Pending)
            ->with('user')
            ->latest()
            ->get();
 
        return view('pages.dashboard.admin.contracts.index', compact('pendingProfiles'));
    }
 
    /**
     * Download het geüploade PDF-contract van een bedrijf.
     * 
     * @param CompanyProfile $companyProfile Het bedrijfsprofiel waarvan we het contract downloaden.
     * @return StreamedResponse De file download.
     */
    public function download(CompanyProfile $companyProfile): StreamedResponse
    {
        // Controleer of de file wel echt bestaat in de storage
        abort_unless(
            $companyProfile->contract_file_path && Storage::disk('local')->exists($companyProfile->contract_file_path),
            404,
            'Het contractbestand kon niet worden gevonden.'
        );
 
        return Storage::disk('local')->download(
            $companyProfile->contract_file_path, 
            'contract-' . $companyProfile->kvk_number . '.pdf'
        );
    }
 
    /**
     * Keur het contract van een bedrijf goed.
     * 
     * @param CompanyProfile $companyProfile Het bedrijfsprofiel om goed te keuren.
     * @return RedirectResponse Redirect terug met succesmelding.
     */
    public function approve(CompanyProfile $companyProfile): RedirectResponse
    {
        $companyProfile->update([
            'contract_status' => ContractStatus::Approved
        ]);
 
        return back()->with('status', __('Contract goedgekeurd voor :company.', ['company' => $companyProfile->company_name]));
    }
 
    /**
     * Wijs het contract van een bedrijf af.
     * Verwijder het bestand en zet de status terug zodat ze opnieuw kunnen aanvragen.
     * 
     * @param CompanyProfile $companyProfile Het bedrijfsprofiel om af te wijzen.
     * @return RedirectResponse Redirect terug met statusmelding.
     */
    public function decline(CompanyProfile $companyProfile): RedirectResponse
    {
        // Verwijder het bestand uit de storage als deze bestaat
        if ($companyProfile->contract_file_path && Storage::disk('local')->exists($companyProfile->contract_file_path)) {
            Storage::disk('local')->delete($companyProfile->contract_file_path);
        }

        // Status bijwerken en bestandspad leegmaken
        $companyProfile->update([
            'contract_status' => ContractStatus::Rejected,
            'contract_file_path' => null,
        ]);

        return back()->with('status', __('Contract afgewezen voor :company. Het bestand is verwijderd en de gebruiker kan opnieuw uploaden.', ['company' => $companyProfile->company_name]));
    }
}
