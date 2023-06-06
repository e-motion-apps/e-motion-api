<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\CountryService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CountryServiceTest extends TestCase
{
    use DatabaseMigrations;

    public function testStoreCountry(): void
    {
        $name = "Zambezia";
        $altName = "";
        $lat = "10.123";
        $lon = "20.456";
        $iso = "zamb";

        $countryService = new CountryService();
        $countryService->storeCountry($name, $altName, $lat, $lon, $iso);

        $this->assertDatabaseHas("countries", [
            "name" => $name,
            "lat" => $lat,
            "lon" => $lon,
            "iso" => $iso,
        ]);
    }
}
