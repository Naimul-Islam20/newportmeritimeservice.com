<?php

use App\Models\AboutPage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('about_pages', function (Blueprint $table) {
            $table->string('breadcrumb_home_label')->nullable()->after('hero_title');
        });

        $defaults = AboutPage::defaultContent();
        unset($defaults['meta_description']);

        $page = AboutPage::query()->first();
        if (! $page) {
            AboutPage::query()->create($defaults);

            return;
        }

        $fillable = (new AboutPage)->getFillable();
        foreach ($defaults as $key => $value) {
            if (! in_array($key, $fillable, true)) {
                continue;
            }
            $current = $page->getAttribute($key);
            if ($current === null || $current === '') {
                $page->setAttribute($key, $value);
            }
        }
        $page->save();
    }

    public function down(): void
    {
        Schema::table('about_pages', function (Blueprint $table) {
            $table->dropColumn('breadcrumb_home_label');
        });
    }
};
