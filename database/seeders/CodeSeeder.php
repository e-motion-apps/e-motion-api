<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Code;
use Illuminate\Database\Seeder;

class CodeSeeder extends Seeder
{
    public function run(): void
    {
        $codes = [
            ["number" => "204", "description" => "No content."],
            ["number" => "400", "description" => "Bad request."],
            ["number" => "418", "description" => "I'm a teapot."],
            ["number" => "419", "description" => "At least one city has not any coordinates assigned."],
            ["number" => "420", "description" => "At least one city has not been assigned to any country."],
        ];

        foreach ($codes as $code) {
            Code::create([
                "number" => $code["number"],
                "description" => $code["description"],
            ]);
        }
    }
}
