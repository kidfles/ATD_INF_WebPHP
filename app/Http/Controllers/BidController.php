<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests\StoreBidRequest;
use App\Models\Advertisement;
use App\Models\Bid;
use Illuminate\Http\RedirectResponse;
use App\Enums\AdvertisementType;
use Illuminate\View\View;

/**
 * BidController
 * 
 * Verantwoordelijk voor het beheren van biedingen op veiling-advertenties.
 * Bevat logica voor het plaatsen, bekijken en annuleren van biedingen.
 */
class BidController extends Controller
{
    /**
     * Toon een overzicht van alle biedingen die de ingelogde gebruiker heeft geplaatst.
     * 
     * @return \Illuminate\View\View De weergave met de lijst van biedingen.
     */
    public function index(Request $request): View
    {
        $bids = auth()->user()->bids()
            ->filter($request->only(['search', 'sort']))
            ->with(['advertisement.user', 'advertisement.bids']) // Eager loading voor betere prestaties (N+1 voorkomen)
            ->paginate(10)
            ->withQueryString();
            
        return view('pages.dashboard.bids.index', compact('bids'));
    }

    /**
     * Sla een nieuw bod op voor een specifieke advertentie.
     * 
     * @param StoreBidRequest $request Het gevalideerde request met het bodbedrag.
     * @param Advertisement $advertisement De advertentie waarop geboden wordt.
     * @return RedirectResponse Redirect terug met succes- of foutmelding.
     */
    public function store(StoreBidRequest $request, Advertisement $advertisement): RedirectResponse
    {
        $user = $request->user();
        $amount = $request->validated('amount');

        // Check if user has reached the maximum number of bids
        if ($user->bids()->count() >= 4) {
            return back()->with('error', __('You can place a maximum of 4 bids.'));
        }

        // 1. Controleer of de advertentie wel een veiling is
        if ($advertisement->type !== AdvertisementType::Auction) {
            return back()->with('error', __('Bidding is only allowed on auctions.'));
        }

        // 2. Voorkom dat gebruikers op hun eigen advertentie bieden
        if ($advertisement->user_id === $user->id) {
            return back()->with('error', __('You cannot bid on your own advertisement.'));
        }

        // 3. Controleer of het nieuwe bod hoger is dan het huidige hoogste bod
        $highestBid = $advertisement->bids()->max('amount');
        if ($highestBid && $amount <= $highestBid) {
             return back()->withErrors(['amount' => __('Bid must be higher than the current highest bid (€:amount).', ['amount' => $highestBid])]);
        }

        // Maak het bod aan gekoppeld aan de advertentie en gebruiker
        $advertisement->bids()->create([
            'user_id' => $user->id,
            'amount' => $amount,
        ]);

        return back()->with('status', __('Bid successfully placed!'));
    }

    /**
     * Annuleer (verwijder) een geplaatst bod.
     * 
     * @param Bid $bid Het te verwijderen bodmdeel.
     * @return RedirectResponse Redirect terug met statusmelding.
     */
    public function destroy(Bid $bid): RedirectResponse
    {
        // Alleen de eigenaar van het bod mag het annuleren
        abort_unless($bid->user_id === auth()->id(), 403);

        $bid->delete();

        return back()->with('status', __('Bid successfully canceled.'));
    }
}
