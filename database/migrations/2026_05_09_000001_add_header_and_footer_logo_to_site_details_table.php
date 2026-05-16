<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_details', function (Blueprint $table): void {
            $table->string('header_logo_path')->nullable()->after('default_image_path');
            $table->string('footer_logo_path')->nullable()->after('header_logo_path');
        });
    }

    public function down(): void
    {
        Schema::table('site_details', function (Blueprint $table): void {
            $table->dropColumn(['header_logo_path', 'footer_logo_path']);
        });
    }
};
