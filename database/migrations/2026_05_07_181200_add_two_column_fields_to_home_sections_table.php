<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_sections', function (Blueprint $table): void {
            $table->string('two_column_mode')->nullable()->after('variant');
            $table->string('layout_width')->nullable()->after('two_column_mode'); // full, short
        });
    }

    public function down(): void
    {
        Schema::table('home_sections', function (Blueprint $table): void {
            $table->dropColumn(['two_column_mode', 'layout_width']);
        });
    }
};
