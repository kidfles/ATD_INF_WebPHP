<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Bid Model
 * 
 * Dit model vertegenwoordigt een financieel bod dat door een gebruiker is geplaatst op een advertentie.
 */
class Bid extends Model
{
    /** @use HasFactory<\Database\Factories\BidFactory> */
    use HasFactory;

    /**
     * Scope voor het filteren van biedingen.
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? false, function($q, $search) {
            $q->whereHas('advertisement', function($sub) use ($search) {
                $sub->where('title', 'like', "%$search%");
            });
        });

        $query->when($filters['sort'] ?? false, function($q, $sort) {
            match ($sort) {
                'amount_asc' => $q->orderBy('amount', 'asc'),
                'amount_desc' => $q->orderBy('amount', 'desc'),
                'newest' => $q->orderBy('created_at', 'desc'),
                'oldest' => $q->orderBy('created_at', 'asc'),
                default => $q->orderBy('created_at', 'desc'),
            };
        });
    }


    /**
     * De attributen die massaal toegewezen kunnen worden.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'advertisement_id',
        'user_id',
        'amount',
    ];

    /**
     * De attributen die gecast moeten worden naar een specifiek type.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * De advertentie waarop dit bod is geplaatst.
     * 
     * @return BelongsTo De advertentie waartoe dit bod behoort.
     */
    public function advertisement(): BelongsTo
    {
        return $this->belongsTo(Advertisement::class);
    }

    /**
     * De gebruiker die het bod heeft geplaatst.
     * 
     * @return BelongsTo De gebruiker die de bieder is.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
