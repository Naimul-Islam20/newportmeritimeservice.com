<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'designation',
        'company_name',
        'employee_count',
        'modules_needed',
        'email',
        'mobile_no',
        'address',
        'description',
        'status',
    ];

    protected $casts = [
        'modules_needed' => 'array',
    ];
}
