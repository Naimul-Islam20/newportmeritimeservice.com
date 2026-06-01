<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('career_pages', function (Blueprint $table) {
            $table->string('mail_button_label')->nullable()->after('hr_email');
            $table->string('kariyer_button_label')->nullable()->after('kariyer_url');
            $table->string('linkedin_button_label')->nullable()->after('linkedin_url');
            $table->string('aside_image_alt')->nullable()->after('aside_image');
        });
    }

    public function down(): void
    {
        Schema::table('career_pages', function (Blueprint $table) {
            $table->dropColumn([
                'mail_button_label',
                'kariyer_button_label',
                'linkedin_button_label',
                'aside_image_alt',
            ]);
        });
    }
};
