<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests\StoreBidRequest;
use App\Models\Advertisement;
use Illuminate\Http\RedirectResponse;

class BidController extends Controller
{
    public function store(StoreBidRequest $request, Advertisement $advertisement): RedirectResponse
    {
        $advertisement->bids()->create([
            'user_id' => $request->user()->id,
            'amount' => $request->validated('amount'),
        ]);

        return back()->with('status', 'Bid placed successfully!');
    }
}
