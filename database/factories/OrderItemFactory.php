<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $unitPrice = fake()->numberBetween(5_000_000, 30_000_000);
        $quantity = fake()->numberBetween(1, 3);

        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'product_variant_id' => ProductVariant::factory(),
            'product_name' => fake()->words(3, true),
            'sku' => strtoupper(fake()->bothify('???-######')),
            'color_name' => fake()->safeColorName(),
            'storage_label' => fake()->randomElement(['128 GB', '256 GB', 'Không có']),
            'unit_price' => $unitPrice,
            'quantity' => $quantity,
            'line_total' => $unitPrice * $quantity,
        ];
    }
}
