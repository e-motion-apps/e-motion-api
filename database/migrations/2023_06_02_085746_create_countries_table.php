<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create("countries", function (Blueprint $table): void {
            $table->id();
            $table->string("name")->unique();
            $table->string("altName")->nullable();
            $table->string("lat");
            $table->string("lon");
            $table->string("iso");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("countries");
    }
};
