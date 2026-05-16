<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_visual_frames_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('mini_title')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->json('gallery')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_visual_frames_settings');
    }
};
