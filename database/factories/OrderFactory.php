<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->numberBetween(5_000_000, 50_000_000);
        $shippingFee = fake()->randomElement([0, 30_000, 50_000]);

        return [
            'order_code' => 'ORD-'.strtoupper(Str::random(8)),
            'user_id' => User::factory(),
            'receiver_name' => fake()->name(),
            'receiver_phone' => fake()->numerify('09########'),
            'province' => 'TP. Hồ Chí Minh',
            'district' => 'Quận 1',
            'ward' => 'Phường Bến Nghé',
            'address_line' => fake()->streetAddress(),
            'note' => fake()->optional()->sentence(),
            'payment_method' => 'cod',
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'total_amount' => $subtotal + $shippingFee,
            'status' => OrderStatus::Pending,
            'cancelled_at' => null,
            'completed_at' => null,
        ];
    }

    public function status(OrderStatus $status): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => $status,
            'cancelled_at' => $status === OrderStatus::Cancelled ? now() : null,
            'completed_at' => $status === OrderStatus::Completed ? now() : null,
        ]);
    }
}
