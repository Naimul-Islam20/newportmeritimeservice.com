<?php

use App\Models\HomeServiceAreaSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $setting = HomeServiceAreaSetting::query()->first();
        if ($setting) {
            $setting->update([
                'highlight_description' => "We operate 24/7, 365 days a year across Bangladesh's principal commercial ports — Chattogram, Mongla, Payra and Matarbari — providing fast, reliable vessel supply and technical support.",
            ]);
        }
    }

    public function down(): void
    {
        $setting = HomeServiceAreaSetting::query()->first();
        if ($setting) {
            $setting->update([
                'highlight_description' => 'We pride ourselves on our delivery and operate 365 days, 24 hours non-stop in all of the ports and straits of Turkey and the ARA area.',
            ]);
        }
    }
};
