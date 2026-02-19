<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Enums\UserRole;
use App\Enums\ContractStatus;

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
     * Toont whitelabel-onderdelen en advertenties van de zakelijke gebruiker.
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
}
