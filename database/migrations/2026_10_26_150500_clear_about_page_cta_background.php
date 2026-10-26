<?php

use App\Models\AboutPage;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        AboutPage::query()->update([
            'cta_background' => null,
        ]);
    }

    public function down(): void
    {
        // Non-destructive
    }
};
