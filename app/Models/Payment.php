<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'provider',
        'payment_type',
        'merchant_id',
        'request_id',
        'merchant_trade_no',
        'platform_trade_no',
        'amount',
        'product_name',
        'qr_code',
        'qris_url',
        'status',
        'err_code',
        'err_code_des',
        'nmid',
        'rrn',
        'tid',
        'payer',
        'phone_number',
        'issuer_id',
        'expired_time',
        'raw_request',
        'raw_response',
        'notify_payload',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'raw_request' => 'array',
        'raw_response' => 'array',
        'notify_payload' => 'array',
        'paid_at' => 'datetime',
    ];
}
