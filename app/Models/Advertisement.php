<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Advertisement Model
 * 
 * Dit model vertegenwoordigt een advertentie voor verkoop of verhuur.
 * Het bevat filterlogica, relatie-mappings en bedrijfsregels voor reviews.
 */
class Advertisement extends Model
{
    /** @use HasFactory<\Database\Factories\AdvertisementFactory> */
    use HasFactory;

    /**
     * De attributen die massaal toegewezen kunnen worden.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', 
        'title', 
        'description', 
        'price', 
        'type', 
        'image_path', 
        'is_sold',
        'expires_at',
    ];

    /**
     * De attributen die gecast moeten worden.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'is_sold' => 'boolean',
            'price' => 'decimal:2',
        ];
    }

    /**
     * De eigenaar van de advertentie.
     * 
     * @return BelongsTo De gebruiker die deze advertentie heeft geplaatst.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Biedingen geplaatst op deze advertentie.
     * 
     * @return HasMany Een verzameling van biedingen.
     */
    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    /**
     * Verhuurgeschiedenis voor deze advertentie.
     * 
     * @return HasMany Een verzameling van verhuurrecords.
     */
    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    /**
     * Reviews achtergelaten op deze advertentie.
     * Polymorfe relatie.
     * 
     * @return MorphMany Een verzameling van reviews.
     */
    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Bedrijfsregel: Kan deze gebruiker een review achterlaten voor deze advertentie?
     * Logica: Alleen gebruikers die het item hebben gehuurd kunnen een review plaatsen.
     * 
     * @param User $user De gebruiker om te controleren.
     * @return bool True als de gebruiker mag reviewen, anders false.
     */
    public function canBeReviewedBy(User $user): bool
    {
        // Geverifieerde Verhuur check: Een gebruiker moet een bevestigde verhuur
        // gekoppeld aan deze advertentie hebben om een review te mogen plaatsen.
        return $this->rentals()->where('renter_id', $user->id)->exists();
    }

    /**
     * Upsell Relatie: Zelfverwijzende link naar gerelateerde advertenties.
     * Verbindt advertenties via de 'ad_relations' pivot-tabel.
     * 
     * @return BelongsToMany Gerelateerde advertenties.
     */
    public function relatedAds(): BelongsToMany
    {
        return $this->belongsToMany(
            Advertisement::class, 
            'ad_relations',
            'parent_ad_id',
            'child_ad_id'
        );
    }

    /**
     * Scope voor het filteren van advertenties op basis van zoekterm, type, verkoper en sortering.
     * Houdt de query-logica in het model voor betere leesbaarheid.
     * 
     * @param Builder $query De huidige query builder.
     * @param array $filters De filters die toegepast moeten worden.
     * @return void
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        // Zoeken op titel of beschrijving
        $query->when($filters['search'] ?? false, fn($q, $search) => 
            $q->where(fn($sub) => 
                $sub->where('title', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
            )
        );

        // Filteren op type (verkoop/verhuur)
        $query->when($filters['type'] ?? false, fn($q, $type) => 
            $q->where('type', $type)
        );

        // Filteren op verkoper ID
        $query->when($filters['seller'] ?? false, fn($q, $sellerId) => 
            $q->where('user_id', $sellerId)
        );

        // Sorteerlogica
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
