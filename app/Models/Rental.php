<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rental extends Model
{
    /** @use HasFactory<\Database\Factories\RentalFactory> */
    use HasFactory;

    protected $fillable = [
        'advertisement_id',
        'renter_id',
        'start_date',
        'end_date',
        'return_photo_path',
        'wear_and_tear_cost',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'wear_and_tear_cost' => 'decimal:2',
    ];

    public function advertisement(): BelongsTo
    {
        return $this->belongsTo(Advertisement::class);
    }

    public function renter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'renter_id');
    }
}
