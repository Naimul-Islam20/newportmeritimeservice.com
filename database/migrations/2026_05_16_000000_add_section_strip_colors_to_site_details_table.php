<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_details', function (Blueprint $table) {
            $table->string('theme_section_strip_a', 7)->nullable()->after('theme_footer_overlay_opacity');
            $table->string('theme_section_strip_b', 7)->nullable()->after('theme_section_strip_a');
        });
    }

    public function down(): void
    {
        Schema::table('site_details', function (Blueprint $table) {
            $table->dropColumn(['theme_section_strip_a', 'theme_section_strip_b']);
        });
    }
};
