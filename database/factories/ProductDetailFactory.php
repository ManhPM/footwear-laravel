<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductDetail>
 */
class ProductDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'size' => $this->faker->numberBetween(36, 44),
            'quantity' => $this->faker->numberBetween(5, 10),
            'product_id' => $this->faker->unique()->numberBetween(1, 50)
        ];
    }
}
