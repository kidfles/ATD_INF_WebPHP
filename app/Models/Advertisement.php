<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Advertisement extends Model
{
    /** @use HasFactory<\Database\Factories\AdvertisementFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'description', 'price', 'type', 'image_path', 'is_sold'];

    // RELATION: An ad belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    // Helper to check if a specific user can review this ad (must have rented it)
    public function canBeReviewedBy(User $user): bool
    {
        // Check if user has a rental record for this ad
        return $this->rentals()->where('renter_id', $user->id)->exists();
    }

    // RELATION: Self-referencing Many-to-Many (Upsells)
    public function relatedAds()
    {
        return $this->belongsToMany(
            Advertisement::class, 
            'ad_relations',      // Pivot table name
            'parent_ad_id',      // Foreign key 1
            'child_ad_id'        // Foreign key 2
        );
    }

    // SCOPE: Filter Logic (Keeps Controller Clean)
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? false, fn($q, $search) => 
            $q->where(fn($sub) => 
                $sub->where('title', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
            )
        );

        $query->when($filters['type'] ?? false, fn($q, $type) => 
            $q->where('type', $type)
        );

        $query->when($filters['seller'] ?? false, fn($q, $sellerId) => 
            $q->where('user_id', $sellerId)
        );

        $query->when($filters['sort'] ?? false, function($q, $sort) {
            match ($sort) {
                'price_asc' => $q->orderBy('price', 'asc'),
                'price_desc' => $q->orderBy('price', 'desc'),
                'newest' => $q->orderBy('created_at', 'desc'),
                default => $q->orderBy('created_at', 'desc'),
            };
        });
    }
}
