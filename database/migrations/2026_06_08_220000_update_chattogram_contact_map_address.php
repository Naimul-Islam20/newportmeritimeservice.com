<?php

use App\Models\WhereWeAreLocation;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $coords = '22.3279216,91.8160141';

        WhereWeAreLocation::query()
            ->where('slug', 'chattogram-port')
            ->update([
                'map_query' => $coords,
                'map_embed' => null,
                'office_title' => 'Hasna Tower, Agrabad C/A',
            ]);
    }

    public function down(): void
    {
        WhereWeAreLocation::query()
            ->where('slug', 'chattogram-port')
            ->update([
                'map_query' => null,
                'office_title' => 'Chattogram Port Authority',
            ]);
    }
};
