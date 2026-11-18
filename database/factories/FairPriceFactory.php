<?php

namespace Database\Factories;

use App\Models\FairPrice;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FairPrice>
 */
class FairPriceFactory extends Factory
{
    protected $model = FairPrice::class;

    public function definition(): array
    {
        $min = fake()->numberBetween(100000, 500000);
        $max = $min + fake()->numberBetween(150000, 500000);
        $recommended = (int) round(($min + $max) / 2);

        return [
            'product_id' => Product::factory(),
            'min_price' => $min,
            'max_price' => $max,
            'recommended_price' => $recommended,
            'material_cost' => (int) round($recommended * 0.25),
            'labor_cost' => (int) round($recommended * 0.65),
            'artisan_share_percent' => 90,
            'notes' => 'Rentang harga wajar terverifikasi kurator fair-trade UEMKAEM.',
            'is_verified' => true,
        ];
    }
}
