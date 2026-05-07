<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_sections', function (Blueprint $table): void {
            $table->string('button_label')->nullable()->after('title');
            $table->string('button_url', 2048)->nullable()->after('button_label');
        });
    }

    public function down(): void
    {
        Schema::table('home_sections', function (Blueprint $table): void {
            $table->dropColumn(['button_label', 'button_url']);
        });
    }
};
