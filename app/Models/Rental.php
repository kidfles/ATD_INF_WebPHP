<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
}
