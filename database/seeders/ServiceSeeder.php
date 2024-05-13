<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ServicesEnum;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        foreach (ServicesEnum::cases() as $service) {
            Service::query()->create([
                "type" => $service->value,
            ]);
        }
    }
}
