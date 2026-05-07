<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_details', function (Blueprint $table): void {
            $table->text('map')->nullable()->after('location');
            $table->string('default_image_path')->nullable()->after('social_links');
        });
    }

    public function down(): void
    {
        Schema::table('site_details', function (Blueprint $table): void {
            $table->dropColumn(['map', 'default_image_path']);
        });
    }
};
