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
        $bids = auth()->user()->bids()->with('advertisement')->latest()->get();
        return view('pages.dashboard.bids.index', compact('bids'));
    }

    public function store(StoreBidRequest $request, Advertisement $advertisement): RedirectResponse
    {
        $advertisement->bids()->create([
            'user_id' => $request->user()->id,
            'amount' => $request->validated('amount'),
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
