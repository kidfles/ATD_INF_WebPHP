<?php declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        return view('pages.dashboard.index', [
            // "Mijn Activiteit" - zaken die ik heb gekocht/gehuurd
            'myRentals' => $user->rentals()->with('advertisement')->latest()->get(),
            'myBids'    => $user->bids()->with('advertisement')->latest()->take(5)->get(),
            'myBidsCount' => $user->bids()->count(),
            'myAds'     => $user->advertisements()->latest()->take(5)->get(),
            
            // "Mijn Verkoop" - zaken die anderen bij mij hebben gekocht/gehuurd
            'incomingRentals' => Rental::whereHas('advertisement', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->with(['advertisement', 'renter'])->get(),
        ]);
    }
}
