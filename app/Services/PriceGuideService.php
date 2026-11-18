<?php

namespace App\Services;

/**
 * Rumus (transparan):
 *   biaya tenaga kerja = jam kerja x tarif upah per jam (dibulatkan ke rupiah)
 *   estimasi harga     = biaya bahan + biaya tenaga kerja
 * Estimasi lalu dibandingkan dengan aturan harga adil melalui PriceCheckService.
 */
class PriceGuideService
{
    public function __construct(private PriceCheckService $checker) {}

    public function estimate(int $categoryId, int $materialId, int $materialCost, float $laborHours, int $hourlyRate): array
    {
        $laborCost = (int) round($laborHours * $hourlyRate);
        $total = $materialCost + $laborCost;

        return [
            'material_cost' => $materialCost,
            'labor_hours' => $laborHours,
            'hourly_rate' => $hourlyRate,
            'labor_cost' => $laborCost,
            'estimated_price' => $total,
            'check' => $this->checker->check($categoryId, $materialId, $total),
        ];
    }
}
