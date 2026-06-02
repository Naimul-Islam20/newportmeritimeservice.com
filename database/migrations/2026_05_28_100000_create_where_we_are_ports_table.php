<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('where_we_are_ports', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('where_we_are_location_id')
                ->constrained('where_we_are_locations')
                ->cascadeOnDelete();
            $table->string('slug');
            $table->string('title');
            $table->text('meta_description')->nullable();
            $table->json('body_paragraphs')->nullable();
            $table->string('footer_link_label')->nullable();
            $table->string('footer_link_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['where_we_are_location_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('where_we_are_ports');
    }
};
