<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Http\Requests\StoreAdvertisementRequest;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function index(Request $request)
    {
        // 1. Filter, 2. Sort, 3. Eager Load User, 4. Paginate
        $advertisements = Advertisement::filter($request->only(['search', 'type', 'sort']))
            ->with('user') // Prevents N+1 Query Problem
            ->paginate(12)
            ->withQueryString(); // Keeps search params in pagination links

        return view('advertisements.index', compact('advertisements'));
    }

    public function create()
    {
        // Check limit again for UI (optional, good UX)
        if (auth()->user()->advertisements()->count() >= 4) {
            return redirect()->route('dashboard')->with('error', 'Maximum advertenties bereikt.');
        }
        return view('advertisements.create');
    }

    public function store(StoreAdvertisementRequest $request)
    {
        // The data is already validated and authorized here!
        $data = $request->validated();
        
        // Handle Image Upload
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('ads', 'public');
        }

        // Create via Relationship (Automatically sets user_id)
        $request->user()->advertisements()->create($data);

        return redirect()->route('advertisements.index')
            ->with('success', 'Advertentie succesvol aangemaakt!');
    }
}
