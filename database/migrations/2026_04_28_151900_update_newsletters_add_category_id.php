<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('newsletters', function (Blueprint $table): void {
            $table->foreignId('category_id')->nullable()->after('title')->constrained('newsletter_categories')->nullOnDelete();
        });

        DB::table('newsletters')
            ->select('id', 'category')
            ->orderBy('id')
            ->get()
            ->each(function (object $newsletter): void {
                if (! filled($newsletter->category)) {
                    return;
                }

                $categoryId = DB::table('newsletter_categories')->updateOrInsert(
                    ['name' => $newsletter->category],
                    ['updated_at' => now(), 'created_at' => now()]
                );

                $category = DB::table('newsletter_categories')->where('name', $newsletter->category)->first();

                DB::table('newsletters')
                    ->where('id', $newsletter->id)
                    ->update(['category_id' => $category?->id]);
            });

        Schema::table('newsletters', function (Blueprint $table): void {
            $table->dropColumn('category');
        });
    }

    public function down(): void
    {
        Schema::table('newsletters', function (Blueprint $table): void {
            $table->string('category', 120)->nullable()->after('title');
        });

        DB::table('newsletters')
            ->leftJoin('newsletter_categories', 'newsletters.category_id', '=', 'newsletter_categories.id')
            ->update(['newsletters.category' => DB::raw('newsletter_categories.name')]);

        Schema::table('newsletters', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('category_id');
        });
    }
};
