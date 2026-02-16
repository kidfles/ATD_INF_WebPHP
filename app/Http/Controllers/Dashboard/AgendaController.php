<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Advertisement;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    /**
     * Toon de agenda-pagina met de FullCalendar kalender.
     */
    public function index()
    {
        return view('pages.dashboard.agenda');
    }

    /**
     * Lever een JSON-feed van events voor FullCalendar.
     * FullCalendar stuurt automatisch 'start' en 'end' parameters mee.
     */
    public function events(Request $request)
    {
        $user = $request->user();
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
                'end'   => $rental->end_date->addDay()->toDateString(), // FullCalendar end is exclusive
                'color' => $user->companyProfile->brand_color ?? '#4f46e5',
                'extendedProps' => ['type' => 'rental'],
            ];
        }

        // 2. Verloopdatums van advertenties
        $expiries = $user->advertisements()
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

        return response()->json($events);
    }
}
