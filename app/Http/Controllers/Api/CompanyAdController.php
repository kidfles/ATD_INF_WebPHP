<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanyAdController extends Controller
{
    /**
     * Haal alle advertenties op van het bedrijf van de geauthenticeerde gebruiker.
     * 
     * @param Request $request Het huidige HTTP request.
     * @return \Illuminate\Http\JsonResponse Een JSON response met de advertenties.
     */
    public function index(Request $request)
    {
        // Retourneer alleen advertenties die eigendom zijn van de ingelogde gebruiker
        $ads = $request->user()->advertisements()->latest()->paginate(20);

        return response()->json($ads);
    }
}
