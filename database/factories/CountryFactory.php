<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
{
    public function definition(): array
    {
        return [
            "name" => fake()->unique()->city(),
            "alternative_name" => fake()->unique()->city(),
            "latitude" => fake()->latitude(),
            "longitude" => fake()->longitude(),
            "iso" => fake()->unique()->languageCode(),
        ];
    }
}
