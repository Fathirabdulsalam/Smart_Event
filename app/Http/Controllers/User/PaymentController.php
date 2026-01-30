<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\EventTicket;
use App\Models\Transaction;
use App\Services\Paylabs\PaylabsQrisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    /**
     * Buat permintaan pembayaran ke Paylabs (QRIS)
     */
    public function pay(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'ticket_id' => 'required|exists:event_tickets,id',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        // Ambil tiket
        $ticket = EventTicket::with('event')->findOrFail($validated['ticket_id']);

        // Hitung total (pastikan harga integer dalam Rupiah)
        $totalAmount = $ticket->price * $validated['quantity'];

        if ($totalAmount <= 0) {
            throw ValidationException::withMessages([
                'ticket_id' => 'Harga tiket tidak valid.'
            ]);
        }

        // Buat transaksi (kode otomatis di-generate oleh model)
        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'event_id' => $ticket->event_id,
            'ticket_id' => $ticket->id,
            'quantity' => $validated['quantity'],
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'expired_at' => now()->addMinutes(10), // QRIS biasanya expired cepat
        ]);

        // Siapkan data untuk Paylabs
        $payData = [
            'amount' => $totalAmount,
            'productName' => 'Tiket: ' . $ticket->name,
            'merchantTradeNo' => $transaction->transaction_code, // TRX2601180001
            'notifyUrl' => route('paylabs.notify'), // pastikan route ini ada
            'requestId' => uniqid('req_', true),
        ];

        // Panggil service Paylabs
        $service = new PaylabsQrisService();
        $result = $service->create($payData);

        // Cek apakah berhasil
        if (!isset($result['paylabs']['json']['data']['qrCode'])) {
            // Jika gagal, update status jadi failed
            $transaction->update(['status' => 'failed']);
            return response()->json([
                'message' => 'Gagal membuat pembayaran. Silakan coba lagi.',
                'debug' => $result['paylabs']['json'] ?? null,
            ], 500);
        }

        // Simpan response dari Paylabs (opsional, untuk debugging)
        $transaction->update([
            'paylabs_response' => $result['paylabs']['json'],
        ]);

        // Kembalikan QRIS ke frontend
        return response()->json([
            'success' => true,
            'transaction_code' => $transaction->transaction_code,
            'qr_code' => $result['paylabs']['json']['data']['qrCode'],
            'amount' => $totalAmount,
            'expires_at' => $transaction->expired_at,
            'message' => 'Silakan scan QRIS untuk menyelesaikan pembayaran.',
        ]);
    }
}