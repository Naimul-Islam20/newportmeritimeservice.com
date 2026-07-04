<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('about_pages', function (Blueprint $table): void {
            if (! Schema::hasColumn('about_pages', 'mission_vision_image')) {
                $table->text('mission_vision_image')->nullable()->after('vision_body');
            }
        });
    }

    public function down(): void
    {
        Schema::table('about_pages', function (Blueprint $table): void {
            if (Schema::hasColumn('about_pages', 'mission_vision_image')) {
                $table->dropColumn('mission_vision_image');
            }
        });
    }
};
