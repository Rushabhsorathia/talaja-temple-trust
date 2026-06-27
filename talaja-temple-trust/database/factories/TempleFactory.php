<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TempleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'slug' => $this->faker->unique()->slug(),
            'is_primary' => false,
            'is_active' => true,
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->safeEmail(),
        ];
    }
}
