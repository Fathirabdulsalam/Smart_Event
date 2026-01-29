<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_code',
        'user_id',
        'event_id',
        'ticket_id',
        'quantity',
        'total_amount',
        'status',
        'payment_url',
        'paid_at',
        'expired_at',
        'paylabs_response'
    ];

    protected $casts = [
        'paylabs_response' => 'array',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime'
    ];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function ticket()
    {
        return $this->belongsTo(EventTicket::class);
    }

    // Generate kode transaksi: TRX + YYMMDD + nomor urut
    public static function generateCode()
    {
        $date = now()->format('ymd'); // 260118
        $last = self::whereDate('created_at', today())
                    ->where('transaction_code', 'like', "TRX{$date}%")
                    ->orderBy('id', 'desc')
                    ->first();

        $number = $last ? (intval(substr($last->transaction_code, -4)) + 1) : 1;
        return "TRX{$date}" . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}