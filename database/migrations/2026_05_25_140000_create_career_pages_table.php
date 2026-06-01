<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_pages', function (Blueprint $table) {
            $table->id();
            $table->string('hero_title')->nullable();
            $table->string('hero_background')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('eyebrow')->nullable();
            $table->string('section_title')->nullable();
            $table->json('intro_paragraphs')->nullable();
            $table->string('application_title')->nullable();
            $table->text('application_lead')->nullable();
            $table->json('qualifications')->nullable();
            $table->text('application_note')->nullable();
            $table->string('hr_email')->nullable();
            $table->string('kariyer_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('aside_image')->nullable();
            $table->string('team_button_label')->nullable();
            $table->string('team_button_url')->nullable();
            $table->string('offers_eyebrow')->nullable();
            $table->string('offers_title')->nullable();
            $table->string('offers_card_title')->nullable();
            $table->json('offers_paragraphs')->nullable();
            $table->string('bottom_cta_label')->nullable();
            $table->string('bottom_cta_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_pages');
    }
};
