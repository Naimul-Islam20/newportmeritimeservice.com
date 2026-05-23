<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_service_area_settings', function (Blueprint $table): void {
            $table->string('branches_mini_title')->nullable()->after('steps');
            $table->string('branches_title')->nullable()->after('branches_mini_title');
            $table->string('branches_view_all_label')->nullable()->after('branches_title');
            $table->string('branches_view_all_url')->nullable()->after('branches_view_all_label');
            $table->json('branches_items')->nullable()->after('branches_view_all_url');
        });
    }

    public function down(): void
    {
        Schema::table('home_service_area_settings', function (Blueprint $table): void {
            $table->dropColumn([
                'branches_mini_title',
                'branches_title',
                'branches_view_all_label',
                'branches_view_all_url',
                'branches_items',
            ]);
        });
    }
};
