<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_menus', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->cascadeOnDelete();
            $table->string('label');
            $table->string('url');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Migrate existing self-referential children (menus.parent_id) into sub_menus.
        if (Schema::hasColumn('menus', 'parent_id')) {
            $children = DB::table('menus')->whereNotNull('parent_id')->get();

            foreach ($children as $child) {
                DB::table('sub_menus')->insert([
                    'menu_id' => $child->parent_id,
                    'label' => $child->label,
                    'url' => $child->url,
                    'sort_order' => $child->sort_order ?? 0,
                    'is_active' => (bool) ($child->is_active ?? true),
                    'created_at' => $child->created_at ?? now(),
                    'updated_at' => $child->updated_at ?? now(),
                ]);
            }

            DB::table('menus')->whereNotNull('parent_id')->delete();

            Schema::table('menus', function (Blueprint $table): void {
                // dropConstrainedForeignId is safe if the FK exists.
                $table->dropConstrainedForeignId('parent_id');
            });
        }
    }

    public function down(): void
    {
        // Recreate parent_id on menus (best-effort); data reverse-migration is not guaranteed.
        if (! Schema::hasColumn('menus', 'parent_id')) {
            Schema::table('menus', function (Blueprint $table): void {
                $table->foreignId('parent_id')->nullable()->constrained('menus')->cascadeOnDelete();
            });
        }

        Schema::dropIfExists('sub_menus');
    }
};
