<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Http\Requests\StoreAdvertisementRequest;
use Illuminate\Http\Request;

/**
 * AdvertisementController
 * 
 * Beheert de advertenties voor de ingelogde gebruiker in het dashboard.
 * Dit omvat het bekijken, maken, bijwerken en verwijderen van eigen advertenties.
 */
class AdvertisementController extends Controller
{
    /**
     * Toon een overzicht van de advertenties van de ingelogde gebruiker.
     * 
     * @param Request $request Het huidige HTTP request.
     * @return \Illuminate\View\View De weergave met de lijst van advertenties.
     */
    public function index(Request $request)
    {
        // Dashboard logica: Toon alleen de advertenties van de huidige gebruiker
        $advertisements = $request->user()->advertisements()
            ->filter($request->only(['search', 'type', 'sort']))
            ->paginate(12)
            ->withQueryString();

        return view('pages.dashboard.advertisements.index', compact('advertisements'));
    }

    /**
     * Toon het formulier voor het maken van een nieuwe advertentie.
     * 
     * @return \Illuminate\View\View De weergave van het aanmaakformulier.
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException Als de gebruiker een koper/huurder rol heeft.
     */
    public function create()
    {
        // Bedrijfsregel: Kopers/huurders (rol 'user') mogen geen advertenties plaatsen
        if (auth()->user()->role === 'user') {
            abort(403, 'Als koper/huurder kun je geen advertenties plaatsen.');
        }

        // Haal alle advertenties van de gebruiker op voor de gerelateerde advertenties selectie
        $myAdvertisements = auth()->user()->advertisements()->select('id', 'title', 'type')->get();

        return view('pages.dashboard.advertisements.create', compact('myAdvertisements'));
    }

    /**
     * Sla een nieuwe advertentie op in de database.
     * 
     * @param StoreAdvertisementRequest $request Het gevalideerde Store request.
     * @return \Illuminate\Http\RedirectResponse Redirect naar het overzicht.
     */
    public function store(StoreAdvertisementRequest $request)
    {
        // Extra veiligheidscheck voor rol
        if ($request->user()->role === 'user') {
            abort(403, 'Als koper/huurder kun je geen advertenties plaatsen.');
        }

        $data = $request->validated();
        
        // Afbeelding upload afhandelen
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('ads', 'public');
        }

        // Aanmaken via de relatie zodat user_id automatisch correct staat
        $advertisement = $request->user()->advertisements()->create($data);

        // Synchroniseer gerelateerde advertenties (upsells)
        if ($request->has('related_ads')) {
            $advertisement->relatedAds()->sync($request->input('related_ads'));
        }

        return redirect()->route('dashboard.advertisements.index')
            ->with('success', __('Advertisement successfully created!'));
    }

    /**
     * Toon een specifieke advertentie in detail (voor beheer).
     * 
     * @param Advertisement $advertisement Het advertentiemodel.
     * @return \Illuminate\View\View De detailweergave.
     */
    public function show(Advertisement $advertisement)
    {
        // Toegang beperken tot de eigenaar
        if ($advertisement->user_id !== auth()->id()) {
            abort(403);
        }
        return view('pages.dashboard.advertisements.show', compact('advertisement'));
    }

    /**
     * Toon het formulier voor het bewerken van een bestaande advertentie.
     * 
     * @param Advertisement $advertisement Het advertentiemodel.
     * @return \Illuminate\View\View De bewerkingsweergave.
     */
    public function edit(Advertisement $advertisement)
    {
        // Eigendom controleren
        if ($advertisement->user_id !== auth()->id()) {
            abort(403);
        }

        // Haal alle eigen advertenties op behalve de huidige (zelfreferentie voorkomen)
        $myAdvertisements = auth()->user()->advertisements()
            ->where('id', '!=', $advertisement->id)
            ->select('id', 'title', 'type')
            ->get();

        return view('pages.dashboard.advertisements.edit', compact('advertisement', 'myAdvertisements'));
    }

    /**
     * Werk een bestaande advertentie bij in de database.
     * 
     * @param \App\Http\Requests\UpdateAdvertisementRequest $request Het gevalideerde Update request.
     * @param Advertisement $advertisement Het advertentiemodel.
     * @return \Illuminate\Http\RedirectResponse Redirect naar het overzicht.
     */
    public function update(\App\Http\Requests\UpdateAdvertisementRequest $request, Advertisement $advertisement)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('ads', 'public');
        }

        $advertisement->update($data);

        // Synchroniseer gerelateerde advertenties
        $advertisement->relatedAds()->sync($request->input('related_ads', []));

        return redirect()->route('dashboard.advertisements.index')
            ->with('success', __('Advertisement updated!'));
    }

    /**
     * Verwijder een advertentie uit de database.
     * 
     * @param Advertisement $advertisement Het advertentiemodel.
     * @return \Illuminate\Http\RedirectResponse Redirect naar het overzicht.
     */
    public function destroy(Advertisement $advertisement)
    {
        // Alleen de eigenaar mag zijn eigen advertentie verwijderen
        if ($advertisement->user_id !== auth()->id()) {
            abort(403);
        }

        $advertisement->delete();

        return redirect()->route('dashboard.advertisements.index')
            ->with('success', __('Advertisement deleted!'));
    }
}
