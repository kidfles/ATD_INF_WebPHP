<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Http\Requests\StoreAdvertisementRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Filter scope handles search & sort. Paginate by 10.
        $advertisements = Advertisement::filter(request(['search', 'sort']))
            ->with('user') // Eager load user
            ->paginate(10)
            ->withQueryString(); // Keep filters in pagination links

        return view('advertisements.index', compact('advertisements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // For upsells, get user's other ads to link to?
        // Or all ads? "Kettingzaag + Olie" implies checking other ads.
        // Let's pass all ads for now (or maybe just those not yet linked).
        // For scalability, this should be an AJAX search, but for <50 items, all() is fine.
        $candidates = Advertisement::where('user_id', auth()->id())->get(); // Link to OWN ads usually?
        // Actually, you usually cross-sell your own stuff.
        
        return view('advertisements.create', compact('candidates'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdvertisementRequest $request): RedirectResponse
    {
        // Validation & "Max 4" check passed in FormRequest
        
        $advertisement = $request->user()->advertisements()->create($request->validated());
        
        // Sync upsells
        if ($request->has('upsells')) {
            $advertisement->upsells()->sync($request->input('upsells'));
        }

        return redirect()->route('advertisements.index')
            ->with('status', 'Advertisement created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Advertisement $advertisement): View
    {
        $advertisement->load(['user', 'upsells']);
        return view('advertisements.show', compact('advertisement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Advertisement $advertisement): View
    {
        if ($request->user()->cannot('update', $advertisement)) {
             abort(403);
        }
        
        $candidates = Advertisement::where('user_id', auth()->id())
                        ->where('id', '!=', $advertisement->id)
                        ->get();
                        
        return view('advertisements.edit', compact('advertisement', 'candidates'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreAdvertisementRequest $request, Advertisement $advertisement): RedirectResponse
    {
        // Validated & Authorized (policy check in request or here?)
        // Request authorize() for updates checks ownership.
        
        $advertisement->update($request->validated());

        if ($request->has('upsells')) {
             $advertisement->upsells()->sync($request->input('upsells'));
        }

        return redirect()->route('advertisements.index')
            ->with('status', 'Advertisement updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Advertisement $advertisement)
    {
        if (auth()->id() !== $advertisement->user_id) {
             abort(403);
        }
        
        $advertisement->delete();

        return redirect()->route('advertisements.index')
            ->with('status', 'Advertisement deleted!');
    }
}
