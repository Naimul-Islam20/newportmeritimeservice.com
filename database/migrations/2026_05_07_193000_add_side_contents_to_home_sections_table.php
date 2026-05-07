<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_sections', function (Blueprint $table): void {
            $table->json('left_content')->nullable()->after('points');
            $table->json('right_content')->nullable()->after('left_content');
        });
    }

    public function down(): void
    {
        Schema::table('home_sections', function (Blueprint $table): void {
            $table->dropColumn(['left_content', 'right_content']);
        });
    }
};
