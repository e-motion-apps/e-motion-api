<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create("provider_lists", function (Blueprint $table): void {
            $table->id();
            $table->string("name");
            $table->string("url")->nullable();
            $table->string("color");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("provider_lists");
    }
};
