<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NewsletterCategory extends Model
{
    protected $fillable = [
        'name',
    ];

    public function newsletters(): HasMany
    {
        return $this->hasMany(Newsletter::class, 'category_id');
    }
}
