<?php

namespace Database\Factories;

use App\Models\Artisan;
use App\Models\Category;
use App\Models\Product;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = 'Kriya ' . fake()->words(3, true) . ' ' . fake()->unique()->numberBetween(1, 99999);

        return [
            'category_id' => Category::factory(),
            'region_id' => Region::factory(),
            'artisan_id' => Artisan::factory(),
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'artisan_name' => fn (array $attributes) => isset($attributes['artisan_id']) && is_int($attributes['artisan_id'])
                ? (Artisan::find($attributes['artisan_id'])?->name ?? fake()->name())
                : fake()->name(),
            'origin' => fn (array $attributes) => isset($attributes['region_id']) && is_int($attributes['region_id'])
                ? (Region::find($attributes['region_id'])?->name ?? fake()->city())
                : fake()->city(),
            'price' => fake()->numberBetween(150000, 1500000),
            'fair_trade_percent' => fake()->numberBetween(85, 96),
            'stock' => fake()->numberBetween(1, 20),
            'status' => 'published',
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'status' => 'published',
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'status' => 'draft',
        ]);
    }
}
