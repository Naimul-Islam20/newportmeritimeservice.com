<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteDetail extends Model
{
    protected $fillable = [
        'location',
        'map',
        'emails',
        'phones',
        'social_links',
        'default_image_path',
    ];

    protected function casts(): array
    {
        return [
            'emails' => 'array',
            'phones' => 'array',
            'social_links' => 'array',
        ];
    }
}
