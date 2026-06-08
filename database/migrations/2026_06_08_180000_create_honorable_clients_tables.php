<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('honorable_client_pages', function (Blueprint $table): void {
            $table->id();
            $table->string('hero_title')->default('Honorable Clients');
            $table->string('hero_background')->nullable();
            $table->text('page_intro')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('honorable_clients', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('logo_path')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('honorable_clients');
        Schema::dropIfExists('honorable_client_pages');
    }
};
