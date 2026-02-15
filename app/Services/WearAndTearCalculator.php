<?php

namespace App\Services;

use App\Models\Rental;
use Carbon\Carbon;

class WearAndTearCalculator
{
    public function calculate(Rental $rental): float
    {
        // 1. Calculate Duration
        $start = Carbon::parse($rental->start_date);
        $end = Carbon::parse($rental->end_date);
        $days = $start->diffInDays($end) + 1; // +1 to include first day

        // 2. Base Cost
        $pricePerDay = $rental->advertisement->price;
        $total = $days * $pricePerDay;

        // 3. Late Fee Logic (Example: 10% extra if returned late)
        // In a real scenario, you'd compare $rental->end_date vs Now()
        
        return round($total, 2);
    }
}
