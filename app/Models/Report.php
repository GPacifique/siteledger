<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Report extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'title',
        'description',
        'type',
        'report_date',
        'data',
    ];

    protected $casts = [
        'report_date' => 'date',
        'data' => 'array',
    ];
}
