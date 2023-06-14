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
            CountrySeeder::class,
        ]);
    }
}
