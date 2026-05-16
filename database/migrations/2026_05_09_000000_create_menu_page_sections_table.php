<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('menu_page_sections');

        Schema::create('menu_page_sections', function (Blueprint $table): void {
            $table->id();

            $table->string('sectionable_type');
            $table->unsignedBigInteger('sectionable_id');

            $table->string('type', 64);
            $table->string('title')->nullable();
            $table->json('data')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['sectionable_type', 'sectionable_id', 'sort_order'], 'mps_sectionable_sort');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_page_sections');
    }
};
