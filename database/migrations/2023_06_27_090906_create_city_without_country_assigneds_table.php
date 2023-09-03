<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create("city_without_assigned_countries", function (Blueprint $table): void {
            $table->id();
            $table->string("city_name");
            $table->string("country_name");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("city_without_assigned_countries");
    }
};
