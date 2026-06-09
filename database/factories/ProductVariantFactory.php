<?php

namespace Database\Factories;

use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StorageOption;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $salePrice = fake()->numberBetween(5_000_000, 45_000_000);
        $originalPrice = fake()->boolean(70) ? $salePrice + fake()->numberBetween(500_000, 3_000_000) : null;

        return [
            'product_id' => Product::factory(),
            'color_id' => Color::factory(),
            'storage_option_id' => StorageOption::factory(),
            'sku' => strtoupper(Str::random(3)).'-'.fake()->unique()->numerify('######'),
            'original_price' => $originalPrice,
            'sale_price' => $salePrice,
            'stock_quantity' => fake()->numberBetween(0, 50),
            'is_active' => true,
        ];
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock_quantity' => 0,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
