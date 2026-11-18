<?php

namespace App\Services;

use App\Models\PriceRule;
use App\Models\Setting;

/**
 * Satu sumber kebenaran untuk aturan harga adil.
 * Dipakai oleh API validasi harga dan Panduan Harga Artisan.
 */
class PriceCheckService
{
    public const LABOR_RATE_KEY = 'labor_hourly_rate';

    /** Bandingkan harga dengan aturan aktif untuk kombinasi kategori + material. */
    public function check(int $categoryId, int $materialId, int $price): array
    {
        $rule = PriceRule::where('category_id', $categoryId)->where('material_id', $materialId)->first();

        if (! $rule) {
            return $this->result('no_rule', null, $price, null,
                'Belum ada aturan harga untuk kombinasi kategori dan material ini, sehingga harga belum dapat divalidasi.');
        }

        if (! $rule->is_active) {
            return $this->result('rule_inactive', null, $price, null,
                'Aturan harga untuk kombinasi ini sedang dinonaktifkan, sehingga harga belum dapat divalidasi.');
        }

        $range = ['min_price' => $rule->min_price, 'max_price' => $rule->max_price];

        if ($price < $rule->min_price) {
            return $this->result('below_minimum', false, $price, $range,
                'Harga ' . $this->rp($price) . ' berada di bawah batas minimum harga adil ' . $this->rp($rule->min_price)
                . ' (kurang ' . $this->rp($rule->min_price - $price) . ').');
        }

        if ($price > $rule->max_price) {
            return $this->result('above_maximum', false, $price, $range,
                'Harga ' . $this->rp($price) . ' berada di atas batas maksimum ' . $this->rp($rule->max_price)
                . ' (lebih ' . $this->rp($price - $rule->max_price) . ').');
        }

        return $this->result('valid', true, $price, $range,
            'Harga ' . $this->rp($price) . ' berada dalam rentang harga adil (' . $this->rp($rule->min_price)
            . ' – ' . $this->rp($rule->max_price) . ').');
    }

    /**
     * Rumus: harga perkiraan = biaya material + (jam kerja × tarif upah per jam).
     * Tarif upah ditetapkan admin (tabel settings); tidak ada nilai bawaan.
     */
    public function estimate(int $categoryId, int $materialId, int $materialCost, float $laborHours): array
    {
        $rate = (int) Setting::getValue(self::LABOR_RATE_KEY, '0');

        if ($rate < 1) {
            return [
                'ready' => false,
                'message' => 'Tarif upah per jam belum ditetapkan oleh admin, sehingga perhitungan belum dapat dilakukan.',
            ];
        }

        $laborCost = (int) round($laborHours * $rate);
        $total = $materialCost + $laborCost;

        return [
            'ready' => true,
            'material_cost' => $materialCost,
            'labor_hours' => $laborHours,
            'labor_rate' => $rate,
            'labor_cost' => $laborCost,
            'estimated_price' => $total,
            'check' => $this->check($categoryId, $materialId, $total),
        ];
    }

    private function result(string $status, ?bool $valid, int $price, ?array $rule, string $message): array
    {
        return ['status' => $status, 'valid' => $valid, 'price' => $price, 'rule' => $rule, 'message' => $message];
    }

    private function rp(int $n): string
    {
        return 'Rp ' . number_format($n, 0, ',', '.');
    }
}
