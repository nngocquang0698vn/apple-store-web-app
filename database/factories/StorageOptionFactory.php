<?php

namespace Database\Factories;

use App\Models\StorageOption;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StorageOption>
 */
class StorageOptionFactory extends Factory
{
    protected $model = StorageOption::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $capacity = fake()->unique()->randomElement([64, 128, 256, 512, 1024]);

        return [
            'label' => $capacity >= 1024 ? '1 TB' : $capacity.' GB',
            'capacity_gb' => $capacity,
            'is_active' => true,
            'sort_order' => $capacity,
        ];
    }
}
