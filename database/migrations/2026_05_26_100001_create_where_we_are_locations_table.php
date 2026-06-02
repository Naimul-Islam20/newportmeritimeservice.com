<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('where_we_are_locations', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('sub_menu_id')->nullable()->constrained('sub_menus')->nullOnDelete();
            $table->string('hero_title');
            $table->string('hero_background')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('eyebrow')->nullable();
            $table->string('office_title')->nullable();
            $table->json('body_paragraphs')->nullable();
            $table->string('brochure_label')->nullable();
            $table->string('brochure_url')->nullable();
            $table->string('brochure_file')->nullable();
            $table->boolean('show_quality_block')->default(true);
            $table->string('quality_block_title')->nullable();
            $table->text('quality_block_lead')->nullable();
            $table->string('contact_cta_label')->nullable();
            $table->string('contact_cta_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('where_we_are_locations');
    }
};
