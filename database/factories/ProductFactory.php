<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSeries;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(4, true);

        return [
            'category_id' => Category::factory(),
            'product_series_id' => null,
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraphs(2, true),
            'specifications' => null,
            'release_year' => fake()->numberBetween(2020, 2026),
            'is_featured' => false,
            'is_active' => true,
        ];
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function forSeries(ProductSeries $series): static
    {
        return $this->state(fn (array $attributes) => [
            'category_id' => $series->category_id,
            'product_series_id' => $series->id,
        ]);
    }
}
