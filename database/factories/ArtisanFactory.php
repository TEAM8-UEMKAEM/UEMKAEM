<?php

namespace Database\Factories;

use App\Models\Artisan;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Artisan>
 */
class ArtisanFactory extends Factory
{
    protected $model = Artisan::class;

    public function definition(): array
    {
        $name = fake()->name() . ' ' . fake()->unique()->numberBetween(1, 9999);

        return [
            'region_id' => Region::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'workshop_name' => 'Sanggar ' . fake()->words(2, true),
            'specialty' => fake()->randomElement(['Tenun Ikat', 'Batik Tulis', 'Gerabah Tanah Liat', 'Ukiran Kayu', 'Anyaman Rotan']),
            'story' => fake()->paragraphs(2, true),
            'location' => fake()->city(),
            'is_verified' => true,
            'verification_status' => 'verified',
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn () => [
            'is_verified' => false,
            'verification_status' => 'unverified',
        ]);
    }
}
