<?php

namespace App\Services;

use App\Models\Rental;
use Carbon\Carbon;

class WearAndTearCalculator
{
    public function calculate(Rental $rental): float
    {
        // 1. Calculate Duration in Days
        // We use 'start_of_day' to ensure we compare dates accurately
        $start = $rental->start_date->startOfDay();
        $end = $rental->end_date->endOfDay();
        
        // If they return it early, they still pay for the booked days? 
        // Let's assume they pay for the *actual* booked duration.
        $bookedDays = $start->diffInDays($end) + 1;

        // 2. Base Cost
        $pricePerDay = $rental->advertisement->price;
        $totalCost = $bookedDays * $pricePerDay;

        // 3. Late Fee Logic
        // "Business Rule: Bij het terugbrengen... slijtage berekend"
        // Let's add a 25% penalty if returned after the end date.
        // Penalty calculation (1.5x price per day for late days)
        if (now()->startOfDay()->gt($end)) {
            $overdueDays = $end->diffInDays(now());
            if ($overdueDays > 0) {
                $penalty = ($pricePerDay * 1.5) * $overdueDays; // 150% price for late days
                $totalCost += $penalty;
            }
        }

        return round((float) $totalCost, 2);
    }
}
