<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create("favorites", function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger("city_id");
            $table->foreign("city_id")
                ->references("id")
                ->on("cities")
                ->onDelete("cascade");

            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");

            $table->unique(["city_id", "user_id"]);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("favorites");
    }
};
