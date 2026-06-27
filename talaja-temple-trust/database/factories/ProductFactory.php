<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->words(2, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'price' => $this->faker->numberBetween(51, 999),
            'stock' => 50,
            'category' => 'Souvenirs',
            'is_active' => true,
        ];
    }
}
