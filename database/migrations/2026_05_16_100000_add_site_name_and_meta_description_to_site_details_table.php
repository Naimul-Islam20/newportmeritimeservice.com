<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_details', function (Blueprint $table): void {
            $table->string('site_name')->nullable()->after('id');
            $table->text('meta_description')->nullable()->after('site_name');
        });
    }

    public function down(): void
    {
        Schema::table('site_details', function (Blueprint $table): void {
            $table->dropColumn(['site_name', 'meta_description']);
        });
    }
};
