<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->string('full_name')->nullable()->after('id');
            $table->string('phone')->nullable()->after('email');
        });

        if (Schema::hasColumn('contact_messages', 'name')) {
            foreach (DB::table('contact_messages')->select(['id', 'name'])->orderBy('id')->get() as $row) {
                DB::table('contact_messages')->where('id', $row->id)->update([
                    'full_name' => $row->name ?? 'Unknown',
                ]);
            }

            Schema::table('contact_messages', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
        });

        foreach (DB::table('contact_messages')->select(['id', 'full_name'])->orderBy('id')->get() as $row) {
            DB::table('contact_messages')->where('id', $row->id)->update([
                'name' => $row->full_name ?? 'Unknown',
            ]);
        }

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropColumn(['full_name', 'phone']);
        });
    }
};
