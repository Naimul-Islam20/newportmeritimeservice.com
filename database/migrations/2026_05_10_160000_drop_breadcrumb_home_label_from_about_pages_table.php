<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('about_pages', function (Blueprint $table) {
            if (Schema::hasColumn('about_pages', 'breadcrumb_home_label')) {
                $table->dropColumn('breadcrumb_home_label');
            }
        });
    }

    public function down(): void
    {
        Schema::table('about_pages', function (Blueprint $table) {
            if (! Schema::hasColumn('about_pages', 'breadcrumb_home_label')) {
                $table->string('breadcrumb_home_label')->nullable()->after('hero_title');
            }
        });
    }
};
