<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table): void {
            $table->longText('page_content')->nullable()->after('description');
        });

        Schema::table('sub_menus', function (Blueprint $table): void {
            $table->longText('page_content')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table): void {
            $table->dropColumn('page_content');
        });

        Schema::table('sub_menus', function (Blueprint $table): void {
            $table->dropColumn('page_content');
        });
    }
};
