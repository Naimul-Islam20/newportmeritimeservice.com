<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_details', function (Blueprint $table): void {
            $table->string('theme_brand_navy', 7)->nullable()->after('footer_logo_path');
            $table->string('theme_brand_navy_mid', 7)->nullable()->after('theme_brand_navy');
            $table->string('theme_brand_accent', 7)->nullable()->after('theme_brand_navy_mid');
            $table->string('theme_brand_accent_hover', 7)->nullable()->after('theme_brand_accent');
            $table->string('theme_brand_topbar_muted', 7)->nullable()->after('theme_brand_accent_hover');
            $table->string('theme_footer_overlay_base', 7)->nullable()->after('theme_brand_topbar_muted');
            $table->unsignedTinyInteger('theme_footer_overlay_opacity')->nullable()->after('theme_footer_overlay_base');
        });
    }

    public function down(): void
    {
        Schema::table('site_details', function (Blueprint $table): void {
            $table->dropColumn([
                'theme_brand_navy',
                'theme_brand_navy_mid',
                'theme_brand_accent',
                'theme_brand_accent_hover',
                'theme_brand_topbar_muted',
                'theme_footer_overlay_base',
                'theme_footer_overlay_opacity',
            ]);
        });
    }
};
