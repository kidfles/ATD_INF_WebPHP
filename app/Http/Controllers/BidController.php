<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests\StoreBidRequest;
use App\Models\Advertisement;
use App\Models\Bid;
use Illuminate\Http\RedirectResponse;

class BidController extends Controller
{
    public function index()
    {
        $bids = auth()->user()->bids()
            ->with(['advertisement.user', 'advertisement.bids']) // Eager load nested
            ->latest()
            ->get();
        return view('pages.dashboard.bids.index', compact('bids'));
    }

    public function store(StoreBidRequest $request, Advertisement $advertisement): RedirectResponse
    {
        $user = $request->user();
        $amount = $request->validated('amount');

        // 1. Ensure it's an auction
        if ($advertisement->type !== 'auction') {
            return back()->with('error', 'Bieden is alleen toegestaan op veilingen.');
        }

        // 2. Prevent self-bidding
        if ($advertisement->user_id === $user->id) {
            return back()->with('error', 'Je kunt niet op je eigen advertentie bieden.');
        }

        // 3. Ensure bid > current highest
        $highestBid = $advertisement->bids()->max('amount');
        if ($highestBid && $amount <= $highestBid) {
             return back()->withErrors(['amount' => "Bod moet hoger zijn dan het huidige hoogste bod (€{$highestBid})."]);
        }

        $advertisement->bids()->create([
            'user_id' => $user->id,
            'amount' => $amount,
        ]);

        return back()->with('status', 'Bid placed successfully!');
    }

    public function destroy(Bid $bid): RedirectResponse
    {
        if ($bid->user_id !== auth()->id()) {
            abort(403);
        }

        $bid->delete();

        return back()->with('status', 'Bid cancelled successfully.');
    }
}
