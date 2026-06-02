<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('where_we_are_locations', function (Blueprint $table): void {
            $table->string('region_label')->nullable()->after('hero_title');
            $table->string('sidebar_label')->nullable()->after('region_label');
            $table->json('sidebar_extras')->nullable()->after('sidebar_label');
            $table->text('brochure_lead')->nullable()->after('brochure_url');
            $table->json('gallery_images')->nullable()->after('body_paragraphs');
            $table->string('certificate_group_slug')->nullable()->after('show_quality_block');
            $table->string('membership_group_slug')->nullable()->after('certificate_group_slug');
            $table->string('body_link_label')->nullable()->after('gallery_images');
            $table->string('body_link_url')->nullable()->after('body_link_label');
        });
    }

    public function down(): void
    {
        Schema::table('where_we_are_locations', function (Blueprint $table): void {
            $table->dropColumn([
                'region_label',
                'sidebar_label',
                'sidebar_extras',
                'brochure_lead',
                'gallery_images',
                'certificate_group_slug',
                'membership_group_slug',
                'body_link_label',
                'body_link_url',
            ]);
        });
    }
};
