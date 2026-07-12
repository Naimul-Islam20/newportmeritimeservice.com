<?php

use App\Models\WhereWeAreLocation;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $address = 'Chattogram Port Authority, Bandar Area, Chattogram 4100, Bangladesh.';

        WhereWeAreLocation::query()
            ->where('slug', 'chattogram-port')
            ->update([
                'office_title' => 'Chattogram Port Authority',
                'contact_address' => $address,
                'contact_map_query' => $address,
                'contact_map_lat' => 22.3391025,
                'contact_map_lng' => 91.8156746,
                'contact_map_zoom' => 18,
                'map_query' => '22.3391025,91.8156746',
                'map_embed' => null,
            ]);
    }

    public function down(): void
    {
        WhereWeAreLocation::query()
            ->where('slug', 'chattogram-port')
            ->update([
                'office_title' => 'Hasna Tower, Agrabad C/A',
                'contact_address' => '1110/B, Hasna Tower (6th Floor), Agrabad C/A, Chittagong.',
                'contact_map_query' => null,
                'contact_map_lat' => 22.3279216,
                'contact_map_lng' => 91.8160141,
                'contact_map_zoom' => 18,
                'map_query' => '22.3279216,91.8160141',
                'map_embed' => null,
            ]);
    }
};
