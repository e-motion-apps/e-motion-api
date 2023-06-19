<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{
    public function definition(): array
    {
        return [
            "name" => fake()->unique()->city(),
            "country_id" => rand(1, 250),
            "latitude" => fake()->latitude(),
            "longitude" => fake()->longitude(),
        ];
    }
}
