<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpertSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_name',
        'designation',
        'mobile',
        'email',
        'status',
    ];
}
