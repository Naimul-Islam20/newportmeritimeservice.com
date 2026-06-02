<?php

use App\Models\WhereWeAreLocation;
use App\Models\WhereWeArePort;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /** @var array<string, string> */
    private array $portQueries = [
        'port-of-rotterdam' => 'Port of Rotterdam, Wilhelminakade, Rotterdam, Netherlands',
        'port-of-antwerp' => 'Port of Antwerp, Belgium',
        'ghent-seaport' => 'Ghent Seaport, Belgium',
        'port-of-dunkirk' => 'Grand Port Maritime of Dunkirk, France',
        'port-of-bremen' => 'Port of Bremen, Germany',
        'port-of-hamburg' => 'Port of Hamburg, Germany',
        'port-of-le-havre' => 'Port of Le Havre, France',
    ];

    public function up(): void
    {
        $rotterdam = WhereWeAreLocation::query()->where('slug', 'rotterdam')->first();
        if ($rotterdam) {
            $rotterdam->update([
                'map_query' => 'Port of Rotterdam, Wilhelminakade, Rotterdam, Netherlands',
            ]);
        }

        foreach ($this->portQueries as $slug => $query) {
            WhereWeArePort::query()->where('slug', $slug)->update(['map_query' => $query]);
        }
    }

    public function down(): void
    {
        WhereWeArePort::query()->whereIn('slug', array_keys($this->portQueries))->update(['map_query' => null]);
        WhereWeAreLocation::query()->where('slug', 'rotterdam')->update(['map_query' => null]);
    }
};
