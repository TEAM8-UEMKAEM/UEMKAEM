<?php

namespace App\Services;

use App\Models\PriceRule;

/**
 * Satu-satunya tempat aturan "harga adil" dievaluasi.
 * Dipakai oleh API, panduan harga artisan, dan (nanti) formulir produk.
 *
 * status: within_range | below_minimum | above_maximum | no_rule | rule_inactive
 * valid : true / false, atau null jika tidak ada aturan aktif untuk dinilai
 */
class PriceCheckService
{
    public function check(int $categoryId, int $materialId, int $price): array
    {
        $rule = PriceRule::where('category_id', $categoryId)->where('material_id', $materialId)->first();

        if (! $rule) {
            return $this->result(null, 'no_rule', 'Belum ada aturan harga adil untuk kombinasi kategori dan material ini.', $price, null);
        }

        if (! $rule->is_active) {
            return $this->result(null, 'rule_inactive', 'Aturan harga adil untuk kombinasi ini sedang dinonaktifkan.', $price, $rule);
        }

        if ($price < $rule->min_price) {
            $gap = $rule->min_price - $price;
            return $this->result(false, 'below_minimum',
                'Harga di bawah batas minimum harga adil (minimum ' . $this->rp($rule->min_price) . '). Kurang ' . $this->rp($gap) . '.',
                $price, $rule, $gap);
        }

        if ($price > $rule->max_price) {
            $gap = $price - $rule->max_price;
            return $this->result(false, 'above_maximum',
                'Harga melebihi batas maksimum harga adil (maksimum ' . $this->rp($rule->max_price) . '). Lebih ' . $this->rp($gap) . '.',
                $price, $rule, $gap);
        }

        return $this->result(true, 'within_range',
            'Harga sesuai rentang harga adil (' . $this->rp($rule->min_price) . ' – ' . $this->rp($rule->max_price) . ').',
            $price, $rule);
    }

    private function result(?bool $valid, string $status, string $message, int $price, ?PriceRule $rule, ?int $difference = null): array
    {
        return [
            'valid' => $valid,
            'status' => $status,
            'message' => $message,
            'submitted_price' => $price,
            'rule' => $rule ? ['min_price' => $rule->min_price, 'max_price' => $rule->max_price] : null,
            'difference' => $difference,
        ];
    }

    private function rp(int $n): string
    {
        return 'Rp ' . number_format($n, 0, ',', '.');
    }
}
