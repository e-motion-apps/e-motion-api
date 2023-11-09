<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create("cities", function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger("country_id");
            $table->foreign("country_id")
                ->references("id")
                ->on("countries")
                ->onDelete("cascade");

            $table->string("name");
            $table->string("slug");
            $table->string("latitude")->nullable();
            $table->string("longitude")->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("cities");
    }
};
