<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sub_menus', function (Blueprint $table): void {
            $table->foreignId('parent_sub_menu_id')
                ->nullable()
                ->after('menu_id')
                ->constrained('sub_menus')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('sub_menus', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('parent_sub_menu_id');
        });
    }
};
