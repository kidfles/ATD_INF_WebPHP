<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ReviewController
 * 
 * Beheert de beoordelingen die gebruikers aan elkaar of aan producten geven.
 * Bevat logica voor zowel productreviews als verkoperreviews.
 */
class ReviewController extends Controller
{
    /**
     * Sla een nieuwe productreview op.
     * 
     * @param Request $request Het request met de rating en het commentaar.
     * @param Advertisement $advertisement De advertentie die beoordeeld wordt.
     * @return \Illuminate\Http\RedirectResponse Redirect terug met succes- of foutmelding.
     */
    public function store(Request $request, Advertisement $advertisement)
    {
        // 1. Invoer valideren
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        // 2. Bedrijfsregel: Heeft de gebruiker dit item daadwerkelijk gehuurd of gekocht?
        if (!$advertisement->canBeReviewedBy($request->user())) {
            return back()->withErrors(['msg' => 'Je mag alleen een review plaatsen als je dit product gekocht of gehuurd hebt.']);
        }

        // 3. Controleer op dubbele reviews (één review per gebruiker per product)
        $existingReview = Review::where('reviewer_id', Auth::id())
            ->where('reviewable_id', $advertisement->id)
            ->where('reviewable_type', Advertisement::class)
            ->exists();

        if ($existingReview) {
            return back()->withErrors(['msg' => 'Je hebt al een review geplaatst voor dit product.']);
        }

        // 4. De review aanmaken (Polymorf gekoppeld aan de advertentie)
        $advertisement->reviews()->create([
            'reviewer_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', __('Review successfully posted!'));
    }

    /**
     * Sla een nieuwe review op voor een verkoper (User).
     * 
     * @param Request $request Het request met de rating en het commentaar.
     * @param User $user De verkoper die beoordeeld wordt.
     * @return \Illuminate\Http\RedirectResponse Redirect terug met succes- of foutmelding.
     */
    public function storeSeller(Request $request, User $user)
    {
        // 1. Invoer valideren
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        // 2. Bedrijfsregel: Alleen reviewen als er een transactie is geweest
        if (!$user->hasSoldTo(Auth::user()) && Auth::id() !== $user->id) {
             return back()->withErrors(['msg' => 'Je mag alleen een verkoper reviewen als je iets bij hen hebt gekocht of gehuurd.']);
        }
        
        // Voorkom dat gebruikers zichzelf beoordelen
        if (Auth::id() === $user->id) {
             return back()->withErrors(['msg' => 'Je kunt jezelf niet reviewen.']);
        }

        // 3. Controleer op dubbele reviews op dit verkopersprofiel
        $existingReview = Review::where('reviewer_id', Auth::id())
            ->where('reviewable_id', $user->id)
            ->where('reviewable_type', User::class)
            ->exists();

        if ($existingReview) {
            return back()->withErrors(['msg' => 'Je hebt deze verkoper al beoordeeld.']);
        }

        // 4. Review aanmaken (Polymorf gekoppeld aan de User)
        $user->reviewsReceived()->create([
            'reviewer_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', __('Review posted on seller profile!'));
    }
}
