<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\City;
use App\Models\Provider;
use App\Models\ProviderList;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    public function run(): void
    {
        $cities = City::all();

        $providerList = ProviderList::all();

        foreach ($cities as $city) {
            foreach ($providerList as $provider) {
                Provider::query()->create([
                    "provider_list_id" => $provider->id,
                    "city_id" => $city->id,
                    "created_by" => "admin",
                ]);
            }
        }
    }
}
