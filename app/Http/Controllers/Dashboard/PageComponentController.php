<?php declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePageComponentRequest;
use App\Http\Requests\BulkUpdatePageComponentsRequest;
use App\Models\PageComponent;
use Illuminate\Http\Request;

class PageComponentController extends Controller
{
    /**
     * Sla een nieuwe paginacomponent op.
     * 
     * @param StorePageComponentRequest $request Het gevalideerde verzoek.
     * @return \Illuminate\Http\RedirectResponse Redirect terug met statusmelding.
     */
    public function store(StorePageComponentRequest $request)
    {
        // Validatie wordt afgehandeld door Form Request

        $company = $request->user()->companyProfile;

        // Definieer standaard inhoud op basis van het type
        $defaultContent = match($request->type) {
            'hero' => ['title' => 'Nieuwe Hero Sectie', 'subtitle' => 'Voeg hier uw ondertitel toe'],
            'text' => ['heading' => 'Over Ons', 'body' => 'Schrijf iets over uw bedrijf.'],
            'featured_ads' => ['limit' => 3],
        };

        // Maak de component aan
        $company->pageComponents()->create([
            'component_type' => $request->type,
            'content' => $defaultContent,
            'order' => $company->pageComponents()->count() + 1,
        ]);

        return back()->with('status', __('New section added! You can now edit it.'));
    }

    /**
     * Werk meerdere componenten tegelijk bij (bulk update).
     * Wordt gebruikt voor het opslaan van de volgorde en inhoud.
     * 
     * @param BulkUpdatePageComponentsRequest $request Het gevalideerde bulk verzoek.
     * @return \Illuminate\Http\RedirectResponse Redirect terug met statusmelding.
     */
    public function bulkUpdate(BulkUpdatePageComponentsRequest $request)
    {
        // Validatie wordt afgehandeld door Form Request

        foreach ($request->ordered_ids as $index => $id) {
            $component = PageComponent::find($id);

            // Beveiligingscheck: hoort deze component bij de ingelogde gebruiker?
            if (!$component || $component->companyProfile->user_id !== $request->user()->id) {
                continue; 
            }

            $updateData = [
                'order' => $index + 1,
            ];

            // Werk inhoud bij indien aanwezig in de components array
            if (isset($request->components[$id]['content'])) {
                $updateData['content'] = $request->components[$id]['content'];
            }

            $component->update($updateData);
        }

        return back()->with('status', __('Page updated successfully!'));
    }

    /**
     * Verwijder een paginacomponent.
     * 
     * @param PageComponent $component De te verwijderen component.
     * @return \Illuminate\Http\RedirectResponse Redirect terug met statusmelding.
     */
    public function destroy(PageComponent $component)
    {
        if ($component->companyProfile->user_id !== auth()->id()) {
            abort(403);
        }

        $component->delete();

        return back()->with('status', __('Section removed.'));
    }
}
