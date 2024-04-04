<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create("city_providers", function (Blueprint $table): void {
            $table->id();
            $table->string("provider_name");
            $table->foreign("provider_name")
                ->references("name")
                ->on("providers")
                ->onDelete("cascade");
            $table->unsignedBigInteger("city_id");
            $table->foreign("city_id")
                ->references("id")
                ->on("cities")
                ->onDelete("cascade");
            $table->string("created_by")->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("city_providers");
    }
};
