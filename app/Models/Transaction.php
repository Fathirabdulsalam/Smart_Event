<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Generate kode transaksi: TRX + YYMMDD + nomor urut
    public static function generateCode()
    {
        return DB::transaction(function () {
            $today = now()->toDateString(); // '2026-01-18'
            $ymd = now()->format('ymd');    // '260118'

            // Ambil sequence terakhir hari ini dengan row lock
            $last = self::where('transaction_date', $today)
                ->lockForUpdate()
                ->orderBy('sequence_number', 'desc')
                ->first();

            $nextSeq = ($last ? $last->sequence_number : 0) + 1;

            return "TRX{$ymd}" . str_pad($nextSeq, 4, '0', STR_PAD_LEFT);
        });
    }

    // 🔁 Isi field otomatis saat create()
    protected static function booted()
    {
        static::creating(function ($transaction) {
            if (empty($transaction->transaction_code)) {
                $code = self::generateCode();
                $transaction->transaction_code = $code;
                $transaction->transaction_date = now()->toDateString();
                $transaction->sequence_number = (int) substr($code, -4);
            }
        });
    }
}