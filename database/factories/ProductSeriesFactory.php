<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\ProductSeries;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ProductSeries>
 */
class ProductSeriesFactory extends Factory
{
    protected $model = ProductSeries::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'category_id' => Category::factory(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'release_year' => fake()->numberBetween(2020, 2026),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
