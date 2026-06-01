<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificate_pages', function (Blueprint $table) {
            $table->id();
            $table->string('hero_title')->default('Quality Certificates & Memberships');
            $table->string('hero_background')->nullable();
            $table->string('page_intro')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('certificate_groups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('intro')->nullable();
            $table->string('layout', 16)->default('grid');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('show_divider_before')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('quality_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificate_group_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('image_path')->nullable();
            $table->string('pdf_path')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quality_certificates');
        Schema::dropIfExists('certificate_groups');
        Schema::dropIfExists('certificate_pages');
    }
};
