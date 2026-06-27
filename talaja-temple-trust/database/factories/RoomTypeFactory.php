<?php

namespace Database\Factories;

use App\Models\Temple;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomTypeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'temple_id' => Temple::factory(),
            'name' => $this->faker->randomElement(['Standard', 'Deluxe', 'Suite']),
            'tariff' => $this->faker->numberBetween(200, 1000),
            'capacity' => $this->faker->numberBetween(2, 4),
            'is_active' => true,
        ];
    }
}
