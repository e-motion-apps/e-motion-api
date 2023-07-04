<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('import_info_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("provider_id");
            $table->unsignedBigInteger("import_id");
            $table->foreign("import_id")
                ->references("id")
                ->on("import_infos")
                ->onDelete("cascade");

            $table->string("code");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_info_details');
    }
};
