<?php declare(strict_types=1);

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
        // 1. Basiskosten (Aantal dagen * Dagprijs)
        // We vertrouwen hier op de logiek in het Rental model
        $bookedDays = $rental->getDaysCount();
        $pricePerDay = (float) ($rental->advertisement?->price ?? 0);
        $baseCost = $bookedDays * $pricePerDay;

        // 2. Boete-logica voor te laat inleveren
        // "Business Rule: Bij het terugbrengen na de einddatum wordt 50% extra boete per dag berekend bovenop de dagprijs."
        // 
        // Voorbeeld:
        // Prijs = 10.00
        // 2 dagen te laat.
        // Extra huurkosten = 2 * 10.00 = 20.00 (Wordt opgeteld bij baseCost)
        // Boete (50%) = 2 * (10.00 * 0.5) = 10.00 (Wordt opgeslagen als lateFee)
        // Totaal extra = 30.00
        $lateFee = 0.00;
        $returnedDate = now()->startOfDay();
        $end = $rental->end_date;

        if ($returnedDate->gt($end)) {
            $overdueDays = $end->diffInDays($returnedDate);
            if ($overdueDays > 0) {
                // 1. Voeg normale huurkosten toe voor de extra dagen.
                // De gebruiker heeft het item immers langer in bezit gehad.
                $additionalBaseCost = $pricePerDay * $overdueDays;
                $baseCost += $additionalBaseCost;

                // 2. Bereken de 50% boete bovenop die extra dagen
                $lateFee = ($pricePerDay * 0.5) * $overdueDays;
            }
        }

        // 3. Slijtagebeleid (Wear & Tear)
        // Particuliere adverteerders hebben geen bedrijfsprofiel en dus geen slijtagebeleid.
        // Standaardbeleid is 'none' (kosten = 0.00).
        $policy = 'none';
        $value = 0.00;

        $company = $rental->advertisement?->user?->companyProfile;
        if ($company) {
            $policy = $company->wear_and_tear_policy ?? 'none';
            $value = $company->wear_and_tear_value ?? 0.00;
        }

        $wearAndTearCost = 0.00;

        if ($policy === 'fixed') {
            $wearAndTearCost = (float) $value;
        } elseif ($policy === 'percentage') {
            // Percentage van de basis huurkosten
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
