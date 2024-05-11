<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use App\Enums\ServicesEnum;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (ServicesEnum::cases() as $service) {
            Service::query()->create([
                "type" => $service->value,
            ]);
        }
    }
}
