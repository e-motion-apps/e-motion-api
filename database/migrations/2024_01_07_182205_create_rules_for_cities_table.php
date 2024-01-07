<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create("rules_for_cities", function (Blueprint $table): void {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger("city_id");
            $table->foreign("city_id")
                ->references("id")
                ->on("cities")
                ->onDelete("cascade");

            $table->unsignedBigInteger("country_id");
            $table->foreign("country_id")
                ->references("id")
                ->on("countries")
                ->onDelete("cascade");

            $table->string("rulesENG");
            $table->string("rulesPL;");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("rules_for_cities");
    }
};
