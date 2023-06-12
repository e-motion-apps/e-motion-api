<?php

declare(strict_types=1);

namespace Tests\Unit;

use Database\Seeders\CountrySeeder;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CountrySeederTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    public function testCountrySeeder(): void
    {
        (new DatabaseSeeder())->call(CountrySeeder::class);

        $file = storage_path("app/countries.json");
        $countriesFromFile = json_decode(file_get_contents($file), true);

        foreach ($countriesFromFile as $countryFromFile) {
            $this->assertDatabaseHas("countries", $countryFromFile);
        }
    }
}
