<?php

use App\Models\HonorableClient;
use App\Models\HonorableClientPage;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /** @var list<string> */
    private array $clients = [
        'YASA SHIPMANAGEMENT & TRADING S.A',
        'TRITHORN BULK A/S',
        'CUNDA SHIPPING',
        'HANIF MARITIME LIMITED',
        'DORIA SHIPPING LIMITED',
        'MEGHNA GROUP OF INDUSTRIES',
        'SEA TRADERS SA',
        'NEW VISION SHIPPING SA',
        'VITA MANAGEMENT S.A.',
        'M/MARITIME CORP',
        'VINASHIP JOINT STOCK COMPANY',
    ];

    public function up(): void
    {
        HonorableClientPage::query()->firstOrCreate([], [
            'hero_title' => 'Honorable Clients',
            'page_intro' => 'We are proud to serve leading maritime companies worldwide. Our honorable clients reflect the trust and long-term partnerships we build across the industry.',
            'meta_description' => 'NewPort Maritime Service honorable clients and trusted shipping partners.',
            'is_active' => true,
        ]);

        foreach ($this->clients as $index => $name) {
            HonorableClient::query()->updateOrCreate(
                ['name' => $name],
                [
                    'sort_order' => $index,
                    'is_active' => true,
                ],
            );
        }
    }

    public function down(): void
    {
        HonorableClient::query()->whereIn('name', $this->clients)->delete();
    }
};
