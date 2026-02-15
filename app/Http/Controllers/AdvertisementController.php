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
        // Check limit again for UI (optional, good UX)
        if (auth()->user()->advertisements()->count() >= 4) {
            return redirect()->route('dashboard.index')->with('error', 'Maximum advertenties bereikt.');
        }
        return view('pages.dashboard.advertisements.create');
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
        // Authorization is handled in the Request or Policy, but we can do a quick check here for UX or use middleware
        if ($advertisement->user_id !== auth()->id()) {
            abort(403);
        }
        return view('pages.dashboard.advertisements.edit', compact('advertisement'));
    }

    public function update(\App\Http\Requests\UpdateAdvertisementRequest $request, Advertisement $advertisement)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('ads', 'public');
        }

        $advertisement->update($data);

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
