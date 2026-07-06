<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public function show()
    {
        return $this->belongsTo(\App\Models\Show::class);
    }

    protected $fillable = [
        'group_name',
        'contact_name',
        'contact_email',
        'contact_phone',
        'group_size',
        'booking_date',
        'booking_time',
        'notes',
        'status',
        'google_form_response_id',
        'show_id',
        'with_presentation',
    ];

    protected $casts = [
        'booking_date'      => 'date',
        'with_presentation' => 'boolean',
    ];
}
