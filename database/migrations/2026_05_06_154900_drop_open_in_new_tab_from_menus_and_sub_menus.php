<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table): void {
            if (Schema::hasColumn('menus', 'open_in_new_tab')) {
                $table->dropColumn('open_in_new_tab');
            }
        });

        Schema::table('sub_menus', function (Blueprint $table): void {
            if (Schema::hasColumn('sub_menus', 'open_in_new_tab')) {
                $table->dropColumn('open_in_new_tab');
            }
        });
    }

    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table): void {
            if (! Schema::hasColumn('menus', 'open_in_new_tab')) {
                $table->boolean('open_in_new_tab')->default(false);
            }
        });

        Schema::table('sub_menus', function (Blueprint $table): void {
            if (! Schema::hasColumn('sub_menus', 'open_in_new_tab')) {
                $table->boolean('open_in_new_tab')->default(false);
            }
        });
    }
};
