<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    public function definition(): array
    {
        return [
            'room_type_id' => \App\Models\RoomType::factory(),
            'number' => $this->faker->unique()->bothify('R##'),
            'housekeeping_status' => 'clean',
            'is_active' => true,
        ];
    }
}
