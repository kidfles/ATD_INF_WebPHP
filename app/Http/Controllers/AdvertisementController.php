<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Http\Requests\StoreAdvertisementRequest;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function index(Request $request)
    {
        // Dashboard Logic: Show ONLY my ads
        $advertisements = $request->user()->advertisements()
            ->filter($request->only(['search', 'type', 'sort']))
            ->paginate(12)
            ->withQueryString();

        return view('pages.dashboard.advertisements.index', compact('advertisements'));
    }

    public function create()
    {
        // Enforce No Selling for Regular Users
        if (auth()->user()->role === 'user') {
            abort(403, 'Als koper/huurder kun je geen advertenties plaatsen.');
        }

        // Fetch all ads of current user for selection list
        $myAdvertisements = auth()->user()->advertisements()->select('id', 'title', 'type')->get();

        return view('pages.dashboard.advertisements.create', compact('myAdvertisements'));
    }

    public function store(StoreAdvertisementRequest $request)
    {
        // Enforce No Selling for Regular Users
        if ($request->user()->role === 'user') {
            abort(403, 'Als koper/huurder kun je geen advertenties plaatsen.');
        }

        // The data is already validated and authorized here!
        $data = $request->validated();
        
        // Handle Image Upload
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('ads', 'public');
        }

        // Create via Relationship (Automatically sets user_id)
        $advertisement = $request->user()->advertisements()->create($data);

        // Sync related ads
        if ($request->has('related_ads')) {
            $advertisement->relatedAds()->sync($request->input('related_ads'));
        }

        return redirect()->route('dashboard.advertisements.index')
            ->with('success', 'Advertentie succesvol aangemaakt!');
    }

    public function show(Advertisement $advertisement)
    {
        if ($advertisement->user_id !== auth()->id()) {
            abort(403);
        }
        return view('pages.dashboard.advertisements.show', compact('advertisement'));
    }

    public function edit(Advertisement $advertisement)
    {
        // Check ownership
        if ($advertisement->user_id !== auth()->id()) {
            abort(403);
        }

        // Fetch all ads EXCEPT current one (cannot link to self)
        $myAdvertisements = auth()->user()->advertisements()
            ->where('id', '!=', $advertisement->id)
            ->select('id', 'title', 'type')
            ->get();

        return view('pages.dashboard.advertisements.edit', compact('advertisement', 'myAdvertisements'));
    }

    public function update(\App\Http\Requests\UpdateAdvertisementRequest $request, Advertisement $advertisement)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('ads', 'public');
        }

        $advertisement->update($data);

        // Sync related ads (if empty array or not present, sync handles it if we pass default [])
        $advertisement->relatedAds()->sync($request->input('related_ads', []));

        return redirect()->route('dashboard.advertisements.index')
            ->with('success', 'Advertentie bijgewerkt!');
    }

    public function destroy(Advertisement $advertisement)
    {
        if ($advertisement->user_id !== auth()->id()) {
            abort(403);
        }

        $advertisement->delete();

        return redirect()->route('dashboard.advertisements.index')
            ->with('success', 'Advertentie verwijderd!');
    }
}
