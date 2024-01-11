<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "name" => "superadmin",
            "email" => env("SUPERADMIN_EMAIL"),
            "email_verified_at" => now(),
            "password" => Hash::make(env('SUPERADMIN_PASSWORD')),
            "remember_token" => Str::random(10),])
            ->assignRole("superadmin")
            ->assignRole("admin");

        User::create([
            "name" => "admin",
            "email" => env("ADMIN_EMAIL"),
            "email_verified_at" => now(),
            "password" => Hash::make(env('ADMIN_PASSWORD')),
            "remember_token" => Str::random(10),])
            ->assignRole("admin");
    }
}
