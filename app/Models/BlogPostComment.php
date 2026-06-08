<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogPostComment extends Model
{
    protected $fillable = [
        'sub_menu_id',
        'author_name',
        'author_email',
        'body',
        'is_approved',
    ];

    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(SubMenu::class, 'sub_menu_id');
    }
}
