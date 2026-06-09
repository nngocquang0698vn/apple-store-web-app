<?php

namespace Database\Factories;

use App\Models\Color;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Color>
 */
class ColorFactory extends Factory
{
    protected $model = Color::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->safeColorName();

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'hex_code' => fake()->hexColor(),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
