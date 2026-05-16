<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteRequest extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'company',
        'vessel_or_reference',
        'request_details',
        'timeline',
        'status',
    ];
}
