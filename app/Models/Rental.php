<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Rental Model
 * 
 * Dit model vertegenwoordigt een huurovereenkomst voor een advertentie van het type 'rent'.
 * Het houdt de duur, foto's en eventuele slijtagekosten bij.
 */
class Rental extends Model
{
    /** @use HasFactory<\Database\Factories\RentalFactory> */
    use HasFactory;

    /**
     * Scope voor het filteren van verhuringen.
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? false, function($q, $search) {
            $q->whereHas('advertisement', function($sub) use ($search) {
                $sub->where('title', 'like', "%$search%");
            });
        });

        $query->when($filters['status'] ?? false, function($q, $status) {
            $now = now()->startOfDay();
            match ($status) {
                'active' => $q->whereNull('return_photo_path')
                             ->where('start_date', '<=', $now)
                             ->where('end_date', '>=', $now),
                'returned' => $q->whereNotNull('return_photo_path'),
                'pending' => $q->whereNull('return_photo_path')
                              ->where('start_date', '>', $now),
                'overdue' => $q->whereNull('return_photo_path')
                              ->where('end_date', '<', $now),
                default => null,
            };
        });

        $query->when($filters['sort'] ?? false, function($q, $sort) {
            match ($sort) {
                'newest' => $q->orderBy('created_at', 'desc'),
                'oldest' => $q->orderBy('created_at', 'asc'),
                'start_asc' => $q->orderBy('start_date', 'asc'),
                'start_desc' => $q->orderBy('start_date', 'desc'),
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
        'renter_id',
        'start_date',
        'end_date',
        'return_photo_path',
        'wear_and_tear_cost',
    ];

    /**
     * De attributen die gecast moeten worden naar een specifiek type.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'wear_and_tear_cost' => 'decimal:2',
    ];

    /**
     * de specifieke advertentie die wordt verhuurd.
     * 
     * @return BelongsTo De advertentie die bij deze verhuur hoort.
     */
    public function advertisement(): BelongsTo
    {
        return $this->belongsTo(Advertisement::class);
    }

    /**
     * De gebruiker die het item huurt.
     * 
     * @return BelongsTo de huurder.
     */
    public function renter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'renter_id');
    }

    /**
     * Bereken de totale huurprijs op basis van de periode en dagprijs.
     * 
     * @return float
     */
    public function getDaysCount(): int
    {
        // Aantal dagen wordt berekend inclusief start- en einddatum (+1)
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    /**
     * Bereken de totale huurprijs op basis van de periode en dagprijs.
     * 
     * @return float
     */
    public function getTotalPriceAttribute(): float
    {
        return $this->getDaysCount() * $this->advertisement->price;
    }

    /**
     * Bepaal de status van de verhuur op basis van datums en inleverstatus.
     * 
     * @return string
     */
    public function getStatusAttribute(): string
    {
        if ($this->return_photo_path) {
            return 'returned';
        }

        $now = now()->startOfDay();

        if ($now->lt($this->start_date)) {
            return 'pending'; // Toekomstige reservering
        }

        if ($now->gt($this->end_date)) {
            return 'overdue';
        }

        return 'active';
    }
}
