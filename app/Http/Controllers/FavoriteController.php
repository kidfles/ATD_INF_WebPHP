<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Advertisement $advertisement)
    {
        // Toggle adds if not exists, removes if exists
        auth()->user()->favorites()->toggle($advertisement->id);

        return back();
    }
    
    public function index()
    {
        $favorites = auth()->user()->favorites()->with('user')->paginate(12);
        return view('pages.dashboard.favorites.index', compact('favorites'));
    }
}
