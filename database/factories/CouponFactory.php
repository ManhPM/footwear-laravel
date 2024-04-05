<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => 'COUPON' . $this->faker->unique()->numberBetween(10, 15),
            'type' => 'money',
            'value' => 30,
            'expery_date' => Carbon::now()->addMonths(2)
        ];
    }
}
