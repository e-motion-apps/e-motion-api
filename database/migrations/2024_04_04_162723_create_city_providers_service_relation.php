<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table("city_providers", function (Blueprint $table): void {
            $table->unsignedBigInteger("service_id")->nullable();
            $table->foreign("service_id")
                ->references("id")
                ->on("services")
                ->onDelete("cascade");
        });
    }
};
