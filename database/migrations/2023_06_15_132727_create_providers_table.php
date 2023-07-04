<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create("providers", function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger("provider_list_id");
            $table->foreign("provider_list_id")
                ->references("id")
                ->on("provider_lists")
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
        Schema::dropIfExists("providers");
    }
};
