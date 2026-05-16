<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_service_area_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('mini_title')->nullable();
            $table->string('title')->nullable();
            $table->string('map_image_path')->nullable();
            $table->string('highlight_title')->nullable();
            $table->text('highlight_description')->nullable();
            $table->json('steps')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_service_area_settings');
    }
};
