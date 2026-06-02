<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('where_we_are_locations', function (Blueprint $table): void {
            $table->text('map_embed')->nullable()->after('gallery_images');
            $table->string('map_query')->nullable()->after('map_embed');
        });

        Schema::table('where_we_are_ports', function (Blueprint $table): void {
            $table->text('map_embed')->nullable()->after('body_paragraphs');
            $table->string('map_query')->nullable()->after('map_embed');
        });
    }

    public function down(): void
    {
        Schema::table('where_we_are_locations', function (Blueprint $table): void {
            $table->dropColumn(['map_embed', 'map_query']);
        });

        Schema::table('where_we_are_ports', function (Blueprint $table): void {
            $table->dropColumn(['map_embed', 'map_query']);
        });
    }
};
