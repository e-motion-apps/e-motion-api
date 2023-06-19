<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CityAlternativeName>
 */
class CityAlternativeNameFactory extends Factory
{
    public function definition(): array
    {
        return [
            "name" => fake()->unique()->city(),
            "city_id" => City::factory(),
        ];
    }
}
