<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_sidebar_settings', function (Blueprint $table) {
            $table->id();
            $table->string('categories_title')->nullable();
            $table->json('nav_groups')->nullable();
            $table->json('nav_links')->nullable();
            $table->string('spare_parts_title')->nullable();
            $table->text('spare_parts_text')->nullable();
            $table->string('spare_parts_button_label')->nullable();
            $table->string('brochures_title')->nullable();
            $table->text('brochures_text')->nullable();
            $table->string('brochure_label')->nullable();
            $table->string('brochure_url')->nullable();
            $table->string('quote_title')->nullable();
            $table->timestamps();
        });

        Schema::create('service_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('path')->unique();
            $table->string('open_nav_group_id')->nullable();
            $table->string('hero_title')->nullable();
            $table->string('hero_background')->nullable();
            $table->string('breadcrumb_label')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('eyebrow')->nullable();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('lead_paragraph')->nullable();
            $table->json('body_paragraphs')->nullable();
            $table->text('highlight_paragraph')->nullable();
            $table->string('services_heading')->nullable();
            $table->json('service_columns')->nullable();
            $table->string('content_image')->nullable();
            $table->string('why_heading')->nullable();
            $table->json('why_paragraphs')->nullable();
            $table->json('why_cards')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_pages');
        Schema::dropIfExists('service_sidebar_settings');
    }
};
