<?php

use App\Models\WhereWeAreLocation;
use App\Models\WhereWeArePort;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * @var list<array{slug: string, title: string, sort_order: int, paragraphs: list<string>}>
     */
    private array $ports = [
        [
            'slug' => 'port-of-rotterdam',
            'title' => 'Port of Rotterdam',
            'sort_order' => 0,
            'paragraphs' => [
                'The Port of Rotterdam is the largest port of Europe, in the province of South Holland. It is a docking station for thousands of ships every day.',
                'Our team supports owners and operators with reliable ship supply, provisions, and coordinated port delivery throughout the ARA region.',
            ],
        ],
        [
            'slug' => 'port-of-antwerp',
            'title' => 'Port of Antwerp',
            'sort_order' => 1,
            'paragraphs' => [
                'Antwerp is one of Europe\'s leading container and general cargo ports, with excellent connectivity to inland waterways and major industrial zones.',
            ],
        ],
        [
            'slug' => 'ghent-seaport',
            'title' => 'Ghent Seaport',
            'sort_order' => 2,
            'paragraphs' => [
                'Ghent Seaport serves the Belgian industrial heartland with efficient vessel turnaround and comprehensive ship supply services.',
            ],
        ],
        [
            'slug' => 'port-of-dunkirk',
            'title' => 'Grand Port Maritime of Dunkirk',
            'sort_order' => 3,
            'paragraphs' => [
                'Dunkirk is a major French port on the North Sea, supporting bulk, ro-ro, and container traffic with strategic links to European markets.',
            ],
        ],
        [
            'slug' => 'port-of-bremen',
            'title' => 'Port of Bremen',
            'sort_order' => 4,
            'paragraphs' => [
                'The Port of Bremen forms part of the Bremen/Bremerhaven complex, serving automotive, breakbulk, and container sectors across northern Germany.',
            ],
        ],
        [
            'slug' => 'port-of-hamburg',
            'title' => 'Port of Hamburg',
            'sort_order' => 5,
            'paragraphs' => [
                'Hamburg is Germany\'s largest seaport and a gateway for trade across Central and Eastern Europe, with extensive logistics and ship supply infrastructure.',
            ],
        ],
        [
            'slug' => 'port-of-le-havre',
            'title' => 'Port of Le Havre',
            'sort_order' => 6,
            'paragraphs' => [
                'Le Havre is France\'s leading container port on the English Channel, offering deep-sea connections and integrated supply chain services.',
            ],
        ],
    ];

    public function up(): void
    {
        $rotterdam = WhereWeAreLocation::query()->where('slug', 'rotterdam')->first();
        if (! $rotterdam) {
            return;
        }

        foreach ($this->ports as $port) {
            WhereWeArePort::query()->updateOrCreate(
                [
                    'where_we_are_location_id' => $rotterdam->id,
                    'slug' => $port['slug'],
                ],
                [
                    'title' => $port['title'],
                    'meta_description' => $port['title'].' — ARA area port services.',
                    'body_paragraphs' => $port['paragraphs'],
                    'footer_link_label' => $port['slug'] === 'port-of-rotterdam'
                        ? 'Newport Ship Supply & Services'
                        : null,
                    'footer_link_url' => $port['slug'] === 'port-of-rotterdam'
                        ? '/where-we-are/rotterdam'
                        : null,
                    'sort_order' => $port['sort_order'],
                    'is_active' => true,
                ],
            );
        }

        $extras = collect($rotterdam->sidebar_extras ?? [])
            ->reject(fn ($e) => is_array($e) && ($e['label'] ?? '') === 'Ports in the ARA area')
            ->values()
            ->all();

        $rotterdam->sidebar_extras = $extras;
        $rotterdam->save();
    }

    public function down(): void
    {
        $rotterdam = WhereWeAreLocation::query()->where('slug', 'rotterdam')->first();
        if (! $rotterdam) {
            return;
        }

        WhereWeArePort::query()
            ->where('where_we_are_location_id', $rotterdam->id)
            ->whereIn('slug', collect($this->ports)->pluck('slug'))
            ->delete();
    }
};
