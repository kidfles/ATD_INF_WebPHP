<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SellerProfileController extends Controller
{
    public function show(User $user)
    {
        // 1. Check if this is a valid seller (Private or Business)
        // We allow 'private_ad' and 'business_ad'. 
        // If strict 'user' role check is needed from snippet, we can add it, but keeping it open is safer for now.
        
        // 2. Load relationships
        $user->load([
            'advertisements' => function($q) {
                $q->latest()->limit(10);
            }, 
            'reviewsReceived.reviewer', // User::reviewsReceived() is the polymorphic relation
            'companyProfile.pageComponents' // Load components IF they are a business
        ]);

        // 3. Determine View Data
        $isBusiness = $user->isBusinessAdvertiser(); // Uses helper from User model
        
        // If business, try to find their custom Hero. If not found, $heroComponent will be null.
        $heroComponent = ($isBusiness && $user->companyProfile)
            ? $user->companyProfile->pageComponents->where('component_type', 'hero')->first() 
            : null;

        // Calculate stats (re-using existing stats logic)
        $averageRating = $user->reviewsReceived()->avg('rating') ?? 0;
        $reviewCount = $user->reviewsReceived()->count();

        return view('pages.seller.profile', compact(
            'user', 
            'isBusiness', 
            'heroComponent',
            'averageRating',
            'reviewCount'
        ));
    }
}
