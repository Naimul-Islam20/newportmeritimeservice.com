<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('about_pages', function (Blueprint $table): void {
            if (! Schema::hasColumn('about_pages', 'mission_image')) {
                $table->text('mission_image')->nullable()->after('vision_body');
            }
            if (! Schema::hasColumn('about_pages', 'vision_image')) {
                $table->text('vision_image')->nullable()->after('mission_image');
            }
        });

        if (Schema::hasColumn('about_pages', 'mission_vision_image')) {
            foreach (DB::table('about_pages')->whereNotNull('mission_vision_image')->get() as $row) {
                $updates = [];
                if (blank($row->mission_image ?? null)) {
                    $updates['mission_image'] = $row->mission_vision_image;
                }
                if (blank($row->vision_image ?? null)) {
                    $updates['vision_image'] = $row->mission_vision_image;
                }
                if ($updates !== []) {
                    DB::table('about_pages')->where('id', $row->id)->update($updates);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::table('about_pages', function (Blueprint $table): void {
            if (Schema::hasColumn('about_pages', 'vision_image')) {
                $table->dropColumn('vision_image');
            }
            if (Schema::hasColumn('about_pages', 'mission_image')) {
                $table->dropColumn('mission_image');
            }
        });
    }
};
