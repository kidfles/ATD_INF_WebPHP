<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanyAdController extends Controller
{
    /**
     * Get all advertisements for the authenticated user's company
     */
    public function index(Request $request)
    {
        // Return only advertisements belonging to the authenticated user
        $ads = $request->user()->advertisements()->latest()->paginate(20);

        return response()->json($ads);
    }
}
