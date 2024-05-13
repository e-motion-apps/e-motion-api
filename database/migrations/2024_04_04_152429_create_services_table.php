<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create("services", function (Blueprint $table): void {
            $table->id();
            $table->string("type");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("services");
    }
};
