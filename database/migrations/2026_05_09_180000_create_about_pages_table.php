<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('about_pages', function (Blueprint $table) {
            $table->id();
            $table->string('hero_title')->nullable();
            $table->text('hero_background')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('trust_image')->nullable();
            $table->text('trust_heading')->nullable();
            $table->longText('trust_paragraph_1')->nullable();
            $table->longText('trust_paragraph_2')->nullable();
            $table->string('stat1_value')->nullable();
            $table->string('stat1_label')->nullable();
            $table->string('stat2_value')->nullable();
            $table->string('stat2_label')->nullable();
            $table->string('stat3_value')->nullable();
            $table->string('stat3_label')->nullable();
            $table->string('mission_title')->nullable();
            $table->longText('mission_body')->nullable();
            $table->string('vision_title')->nullable();
            $table->longText('vision_body')->nullable();
            $table->string('cta_eyebrow')->nullable();
            $table->text('cta_heading')->nullable();
            $table->text('cta_background')->nullable();
            $table->string('cta_button_label')->nullable();
            $table->string('cta_video_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_pages');
    }
};
