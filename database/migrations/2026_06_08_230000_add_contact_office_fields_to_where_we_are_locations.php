<?php

use App\Models\WhereWeAreLocation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('where_we_are_locations', function (Blueprint $table): void {
            if (! Schema::hasColumn('where_we_are_locations', 'contact_address')) {
                $table->text('contact_address')->nullable()->after('map_query');
            }
            if (! Schema::hasColumn('where_we_are_locations', 'contact_map_query')) {
                $table->string('contact_map_query', 500)->nullable()->after('contact_address');
            }
            if (! Schema::hasColumn('where_we_are_locations', 'contact_map_embed')) {
                $table->text('contact_map_embed')->nullable()->after('contact_map_query');
            }
            if (! Schema::hasColumn('where_we_are_locations', 'contact_map_lat')) {
                $table->decimal('contact_map_lat', 10, 7)->nullable()->after('contact_map_embed');
            }
            if (! Schema::hasColumn('where_we_are_locations', 'contact_map_lng')) {
                $table->decimal('contact_map_lng', 10, 7)->nullable()->after('contact_map_lat');
            }
            if (! Schema::hasColumn('where_we_are_locations', 'contact_map_zoom')) {
                $table->unsignedTinyInteger('contact_map_zoom')->nullable()->after('contact_map_lng');
            }
        });

        WhereWeAreLocation::query()
            ->where('slug', 'chattogram-port')
            ->update([
                'contact_address' => '1110/B, Hasna Tower (6th Floor), Agrabad C/A, Chittagong.',
                'contact_map_lat' => 22.3279216,
                'contact_map_lng' => 91.8160141,
                'contact_map_zoom' => 18,
            ]);
    }

    public function down(): void
    {
        Schema::table('where_we_are_locations', function (Blueprint $table): void {
            $columns = [
                'contact_address',
                'contact_map_query',
                'contact_map_embed',
                'contact_map_lat',
                'contact_map_lng',
                'contact_map_zoom',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('where_we_are_locations', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
