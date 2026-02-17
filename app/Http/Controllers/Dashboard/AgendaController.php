<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    /**
     * Toon de agenda-pagina met de FullCalendar kalender.
     */
    public function index()
    {
        $user = auth()->user();
        
        // "Mijn Activiteit" - zaken die ik heb gekocht/gehuurd
        $myRentals = $user->rentals()->with('advertisement')->latest()->get();

        // "Mijn Verkoop" - zaken die anderen bij mij hebben gekocht/gehuurd
        $incomingRentals = \App\Models\Rental::whereHas('advertisement', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with(['advertisement', 'renter'])->get();

        return view('pages.dashboard.agenda', compact('myRentals', 'incomingRentals'));
    }

    /**
     * Lever een JSON-feed van events voor FullCalendar.
     * FullCalendar stuurt automatisch 'start' en 'end' parameters mee.
     */
    public function events(Request $request)
    {
        try {
            $user = $request->user();
            $request->validate([
                'start' => 'required|date',
                'end'   => 'required|date|after_or_equal:start|before:' . now()->addYears(5)->toDateString(),
            ]);

            $events = [];

            // 1. Verhuurperiodes van de producten van deze gebruiker
            $rentals = Rental::whereHas('advertisement', fn($q) => $q->where('user_id', $user->id))
                ->with('advertisement')
                ->where('end_date', '>=', $request->start)
                ->where('start_date', '<=', $request->end)
                ->get();

            foreach ($rentals as $rental) {
                $events[] = [
                    'id'    => 'rental-' . $rental->id,
                    'title' => __('Rental') . ': ' . $rental->advertisement->title,
                    'start' => $rental->start_date->toDateString(),
                    'end'   => $rental->end_date->copy()->addDay()->toDateString(), // FullCalendar end is exclusive
                    'color' => $user->companyProfile?->brand_color ?? '#4f46e5',
                    'extendedProps' => ['type' => 'rental'],
                ];
            }

            // 2. Verloopdatums van advertenties (Only Rent & Auction)
            $expiries = $user->advertisements()
                ->where('type', '!=', 'sell')
                ->whereBetween('expires_at', [$request->start, $request->end])
                ->get();

            foreach ($expiries as $ad) {
                $events[] = [
                    'id'    => 'expiry-' . $ad->id,
                    'title' => __('Expires') . ': ' . $ad->title,
                    'start' => $ad->expires_at->toDateString(),
                    'color' => '#ef4444', // Red for expiry warnings
                    'extendedProps' => ['type' => 'expiry'],
                ];
            }

            // 3. Spullen die ik zelf huur (My Rentals)
            $myRentals = $request->user()->rentals()
                ->whereHas('advertisement')
                ->with('advertisement')
                ->where('end_date', '>=', $request->start)
                ->where('start_date', '<=', $request->end)
                ->get();

            foreach ($myRentals as $rental) {
                $events[] = [
                    'id'    => 'my-rental-' . $rental->id,
                    'title' => __('Renting') . ': ' . $rental->advertisement->title,
                    'start' => $rental->start_date->toDateString(),
                    'end'   => $rental->end_date->copy()->addDay()->toDateString(),
                    'color' => '#10b981', // Green for items I am renting
                    'extendedProps' => ['type' => 'rental'], // Link to rent overview same as others
                ];
            }

            return response()->json($events);
        } catch (\Exception $e) {
            \Log::error('Agenda Error: ' . $e->getMessage());
            return response()->json(['error' => __('An unexpected error occurred. Please try again later.')], 500);
        }
    }
}
