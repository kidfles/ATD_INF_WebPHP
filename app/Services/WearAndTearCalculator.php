<?php

namespace App\Services;

use App\Models\Rental;

class WearAndTearCalculator
{
    /**
     * Bereken de totale kosten inclusief basisprijs, boetes en slijtage.
     * 
     * @param Rental $rental
     * @return array{total: float, breakdown: array{base_cost: float, late_fee: float, wear_and_tear: float}}
     */
    public function calculate(Rental $rental): array
    {
        // 1. Base Cost (Duration in Days * Price)
        // Use inclusive calendar days (00:00 to 00:00 + 1 day)
        $start = $rental->start_date->startOfDay();
        $end = $rental->end_date->startOfDay();
        
        // Count inclusive days
        $bookedDays = $start->diffInDays($end) + 1;
        $pricePerDay = $rental->advertisement->price;
        $baseCost = $bookedDays * $pricePerDay;

        // 2. Late Fee Logic
        // "Business Rule: Bij het terugbrengen... slijtage berekend"
        // Return after end date = 50% extra per day
        $lateFee = 0.00;
        $returnedDate = now()->startOfDay();

        if ($returnedDate->gt($end)) {
            $overdueDays = $end->diffInDays($returnedDate);
            if ($overdueDays > 0) {
                // 150% price for late days (so 50% penalty on top of day price? 
                // Logic: ($pricePerDay * 1.5) * overdueDays.
                $lateFee = ($pricePerDay * 1.5) * $overdueDays;
            }
        }

        // 3. Wear & Tear Policy (New)
        $wearAndTearCost = 0.00;
        $company = $rental->advertisement->user->companyProfile;

        if ($company) {
            if ($company->wear_and_tear_policy === 'fixed') {
                $wearAndTearCost = (float) $company->wear_and_tear_value;
            } elseif ($company->wear_and_tear_policy === 'percentage') {
                // Percentage of base rental cost
                $percentage = (float) $company->wear_and_tear_value;
                $wearAndTearCost = ($baseCost * ($percentage / 100));
            }
        }

        $totalCost = $baseCost + $lateFee + $wearAndTearCost;

        return [
            'total' => round((float) $totalCost, 2),
            'breakdown' => [
                'base_cost' => round((float) $baseCost, 2),
                'late_fee'  => round((float) $lateFee, 2),
                'wear_and_tear' => round((float) $wearAndTearCost, 2),
            ]
        ];
    }
}
