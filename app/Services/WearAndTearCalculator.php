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
        // Rely purely on the Model's unified logic
        $bookedDays = $rental->getDaysCount();
        $pricePerDay = $rental->advertisement->price;
        $baseCost = $bookedDays * $pricePerDay;

        // 2. Late Fee Logic
        // "Business Rule: Bij het terugbrengen na de einddatum wordt 50% extra boete per dag berekend bovenop de dagprijs."
        // 
        // Example:
        // Price = 10.00
        // Late for 2 days.
        // Extra rental cost = 2 * 10.00 = 20.00 (Added to baseCost)
        // Late penalty (50%) = 2 * (10.00 * 0.5) = 10.00 (Recorded as lateFee)
        // Total extra = 30.00
        $lateFee = 0.00;
        $returnedDate = now()->startOfDay();

        if ($returnedDate->gt($end)) {
            $overdueDays = $end->diffInDays($returnedDate);
            if ($overdueDays > 0) {
                // 1. Add normal rental cost for the overdue days to the base count
                // as the user essentially extended the rental.
                $additionalBaseCost = $pricePerDay * $overdueDays;
                $baseCost += $additionalBaseCost;

                // 2. Calculate the 50% penalty on top of that
                $lateFee = ($pricePerDay * 0.5) * $overdueDays;
            }
        }

        // 3. Wear & Tear Policy
        // Private advertisers (no company profile) cannot configure wear & tear.
        // Default policy is 'none' (cost = 0.00).
        $policy = 'none';
        $value = 0.00;

        $company = $rental->advertisement->user->companyProfile;
        if ($company) {
            $policy = $company->wear_and_tear_policy ?? 'none';
            $value = $company->wear_and_tear_value ?? 0.00;
        }

        $wearAndTearCost = 0.00;

        if ($policy === 'fixed') {
            $wearAndTearCost = (float) $value;
        } elseif ($policy === 'percentage') {
            // Percentage of base rental cost
            $percentage = (float) $value;
            $wearAndTearCost = ($baseCost * ($percentage / 100));
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
