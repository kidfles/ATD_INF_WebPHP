<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Order Model
 * 
 * Dit model vertegenwoordigt een afgeronde transactie voor de aankoop van een product.
 * Het verbindt een koper, een verkoper en de specifieke advertentie.
 */
class Order extends Model
{
    /**
     * Scope voor het filteren van bestellingen.
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
                'price_asc' => $q->orderBy('amount', 'asc'),
                'price_desc' => $q->orderBy('amount', 'desc'),
                'newest' => $q->orderBy('created_at', 'desc'),
                'oldest' => $q->orderBy('created_at', 'asc'),
                default => $q->orderBy('created_at', 'desc'),
            };
        });
    }

    /**
     * De attributen die niet massaal toegewezen kunnen worden.
     * Gebruik een lege array om alles toe te staan (guarded aanpak).
     * 
     * @var array<string>|bool
     */
    protected $guarded = [];

    /**
     * De gebruiker die het item heeft gekocht.
     * 
     * @return BelongsTo De koper van het product.
     */
    public function buyer() {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * De gebruiker die het item heeft verkocht.
     * 
     * @return BelongsTo De verkoper van het product.
     */
    public function seller() {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * De specifieke advertentie waar deze bestelling betrekking op heeft.
     * 
     * @return BelongsTo De advertentie van het product.
     */
    public function advertisement() {
        return $this->belongsTo(Advertisement::class);
    }
}
