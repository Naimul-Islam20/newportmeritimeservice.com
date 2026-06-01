<?php

use App\Models\ServicePage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_pages', function (Blueprint $table) {
            $table->string('content_layout', 20)->default('full')->after('slug');
        });

        ServicePage::query()
            ->whereIn('slug', ['transit-delivery', 'port-delivery', 'operations-logistics'])
            ->update(['content_layout' => 'simple']);
    }

    public function down(): void
    {
        Schema::table('service_pages', function (Blueprint $table) {
            $table->dropColumn('content_layout');
        });
    }
};
