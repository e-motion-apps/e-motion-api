<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create(["type" => "bike"]);
        Service::create(["type" => "cargo"]);
        Service::create(["type" => "emoped"]);
        Service::create(["type" => "escooter"]);
        Service::create(["type" => "motorscooter"]);
    }
}
