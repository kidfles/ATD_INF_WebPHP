<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Review Model
 * 
 * Dit model vertegenwoordigt een beoordeling (rating en commentaar) achtergelaten door een gebruiker.
 * Het is een polymorf model dat gekoppeld kan worden aan Advertenties of andere Gebruikers.
 */
class Review extends Model
{
    /** @use HasFactory<\Database\Factories\ReviewFactory> */
    use HasFactory;

    /**
     * De attributen die massaal toegewezen kunnen worden.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reviewer_id',
        'reviewable_id',
        'reviewable_type',
        'rating',
        'comment',
    ];

    /**
     * De gebruiker die de review heeft geschreven.
     * 
     * @return BelongsTo De auteur van de review.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * Het object (Advertentie of Gebruiker) dat wordt beoordeeld.
     * Polymorfe relatie.
     * 
     * @return MorphTo Het beoordeelde object.
     */
    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }
}
