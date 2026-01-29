<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTicket extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'quantity',
        'price',
        'description'
    ];

    // Relasi ke event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}