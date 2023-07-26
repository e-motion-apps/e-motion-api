<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create("import_info_details", function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger("provider_id");
            $table->unsignedBigInteger("import_info_id");
            $table->foreign("import_info_id")
                ->references("id")
                ->on("import_infos")
                ->onDelete("cascade");

            $table->unsignedBigInteger("code");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("import_info_details");
    }
};
