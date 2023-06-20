<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{
    public function definition(): array
    {
        return [
            "name" => fake()->unique()->city(),
            "country_id" => fn() => Country::factory()->create()->id,
            "latitude" => fake()->latitude(),
            "longitude" => fake()->longitude(),
        ];
    }
}
