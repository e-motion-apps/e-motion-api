<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\City;
use App\Models\CityAlternativeName;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CitiesAndCountriesSeeder::class,
        ]);

        City::factory()->count(20)->create();
        CityAlternativeName::factory()->count(10)->create();

        $this->call([
            ProviderListSeeder::class,
            CodeSeeder::class,
            ProviderSeeder::class,
        ]);
    }
}
