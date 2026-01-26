<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'registration_id', 'external_id', 'payment_channel', 
        'payment_method', 'payment_status', 'amount', 
        'total_paid', 'checkout_link', 'paid_at', 'expiry_date'
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
