<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('about_pages', function (Blueprint $table) {
            $table->string('trust_title')->nullable()->after('trust_image');
            $table->longText('trust_description')->nullable()->after('trust_title');
        });

        $rows = DB::table('about_pages')->select('id', 'trust_heading', 'trust_paragraph_1', 'trust_paragraph_2')->get();
        foreach ($rows as $row) {
            $title = is_string($row->trust_heading) ? $row->trust_heading : null;
            $p1 = is_string($row->trust_paragraph_1) ? trim($row->trust_paragraph_1) : '';
            $p2 = is_string($row->trust_paragraph_2) ? trim($row->trust_paragraph_2) : '';
            $desc = $p1 === '' ? $p2 : ($p2 === '' ? $p1 : $p1."\n\n".$p2);
            DB::table('about_pages')->where('id', $row->id)->update([
                'trust_title' => $title,
                'trust_description' => $desc === '' ? null : $desc,
            ]);
        }

        Schema::table('about_pages', function (Blueprint $table) {
            $table->dropColumn(['trust_heading', 'trust_paragraph_1', 'trust_paragraph_2']);
        });
    }

    public function down(): void
    {
        Schema::table('about_pages', function (Blueprint $table) {
            $table->text('trust_heading')->nullable()->after('trust_image');
            $table->longText('trust_paragraph_1')->nullable()->after('trust_heading');
            $table->longText('trust_paragraph_2')->nullable()->after('trust_paragraph_1');
        });

        $rows = DB::table('about_pages')->select('id', 'trust_title', 'trust_description')->get();
        foreach ($rows as $row) {
            $desc = is_string($row->trust_description) ? $row->trust_description : '';
            $parts = preg_split("/\n\s*\n/", $desc, 2);
            $p1 = isset($parts[0]) ? trim($parts[0]) : '';
            $p2 = isset($parts[1]) ? trim($parts[1]) : '';
            DB::table('about_pages')->where('id', $row->id)->update([
                'trust_heading' => is_string($row->trust_title) ? $row->trust_title : null,
                'trust_paragraph_1' => $p1 === '' ? null : $p1,
                'trust_paragraph_2' => $p2 === '' ? null : $p2,
            ]);
        }

        Schema::table('about_pages', function (Blueprint $table) {
            $table->dropColumn(['trust_title', 'trust_description']);
        });
    }
};
