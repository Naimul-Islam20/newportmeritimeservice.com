<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('our_story_pages', function (Blueprint $table) {
            $table->id();
            $table->string('hero_title')->nullable();
            $table->string('hero_background')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('eyebrow')->nullable();
            $table->string('section_title')->nullable();
            $table->json('intro_paragraphs')->nullable();
            $table->json('milestones')->nullable();
            $table->timestamps();
        });

        Schema::create('ceo_message_pages', function (Blueprint $table) {
            $table->id();
            $table->string('hero_title')->nullable();
            $table->string('hero_background')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('eyebrow')->nullable();
            $table->string('salutation')->nullable();
            $table->json('paragraphs')->nullable();
            $table->string('signature_name')->nullable();
            $table->string('signature_role')->nullable();
            $table->string('portrait_path')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->timestamps();
        });

        Schema::create('our_team_pages', function (Blueprint $table) {
            $table->id();
            $table->string('hero_title')->nullable();
            $table->string('hero_background')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('breadcrumb_label')->nullable();
            $table->string('page_title')->nullable();
            $table->json('regional_nav')->nullable();
            $table->json('category_nav')->nullable();
            $table->json('team_sections')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('our_team_pages');
        Schema::dropIfExists('ceo_message_pages');
        Schema::dropIfExists('our_story_pages');
    }
};
