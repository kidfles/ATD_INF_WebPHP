<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Advertisement $advertisement)
    {
        // 1. Validate Input
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        // 2. Business Rule Check: Has the user rented this item?
        // We assume a 'rentals' relationship exists on the User model
        $hasRented = $request->user()->rentals()
            ->where('advertisement_id', $advertisement->id)
            ->exists();

        if (!$hasRented) {
            return back()->withErrors(['msg' => 'Je mag alleen een review plaatsen als je dit product gehuurd hebt.']);
        }

        // 3. Check for Duplicate Reviews (Optional but recommended)
        $existingReview = Review::where('reviewer_id', Auth::id())
            ->where('reviewable_id', $advertisement->id)
            ->where('reviewable_type', Advertisement::class)
            ->exists();

        if ($existingReview) {
            return back()->withErrors(['msg' => 'Je hebt al een review geplaatst voor dit product.']);
        }

        // 4. Create the Review (Polymorphic)
        $advertisement->reviews()->create([
            'reviewer_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            // 'reviewable_type' is automatically set by the relationship if defined correctly in Model
        ]);

        return back()->with('success', 'Review succesvol geplaatst!');
    }
}
