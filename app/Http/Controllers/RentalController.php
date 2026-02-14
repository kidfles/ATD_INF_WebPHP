<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests\StoreRentalRequest;
use App\Models\Advertisement;
use Illuminate\Http\RedirectResponse;

class RentalController extends Controller
{
    public function index()
    {
        $rentals = auth()->user()->rentals()->with('advertisement')->latest()->get();
        return view('pages.dashboard.rentals.index', compact('rentals'));
    }

    public function store(StoreRentalRequest $request, Advertisement $advertisement): RedirectResponse
    {
        // Check availability logic could go here or in a Service
        
        $advertisement->rentals()->create([
            'renter_id' => $request->user()->id,
            'start_date' => $request->validated('start_date'),
            'end_date' => $request->validated('end_date'),
        ]);

        return back()->with('status', 'Rental booked successfully!');
    }
}
