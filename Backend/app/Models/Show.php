<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    protected $fillable = [
        'title',
        'description',
        'age_recommendation',
        'duration_minutes',
        'image',
        'url',
        'language',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
