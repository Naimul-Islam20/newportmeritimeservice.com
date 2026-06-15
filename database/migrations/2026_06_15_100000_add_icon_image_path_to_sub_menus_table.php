<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sub_menus', function (Blueprint $table): void {
            $table->string('icon_image_path')->nullable()->after('cover_image_path');
        });
    }

    public function down(): void
    {
        Schema::table('sub_menus', function (Blueprint $table): void {
            $table->dropColumn('icon_image_path');
        });
    }
};
