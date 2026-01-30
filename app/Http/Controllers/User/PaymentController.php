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
        // 1️⃣ Validasi input
        $validated = $request->validate([
            'ticket_id' => 'required|exists:event_tickets,id',
            'quantity'  => 'required|integer|min:1|max:10',
        ]);

        // 2️⃣ Ambil tiket
        $ticket = EventTicket::with('event')->findOrFail($validated['ticket_id']);

        // 3️⃣ Hitung total harga (Rupiah)
        $totalAmount = $ticket->price * $validated['quantity'];

        if ($totalAmount <= 0) {
            throw ValidationException::withMessages([
                'ticket_id' => 'Harga tiket tidak valid.',
            ]);
        }

        // 4️⃣ Buat transaksi (status awal: pending)
        $transaction = Transaction::create([
            'user_id'      => Auth::id(),
            'event_id'     => $ticket->event_id,
            'ticket_id'    => $ticket->id,
            'quantity'     => $validated['quantity'],
            'total_amount'=> $totalAmount,
            'status'       => 'pending',
            'expired_at'   => now()->addMinutes(10),
        ]);

        // 5️⃣ Payload ke Paylabs
        $payData = [
            'amount'           => $totalAmount,
            'productName'      => 'Tiket: ' . $ticket->name,
            'merchantTradeNo'  => $transaction->transaction_code,
            'notifyUrl'        => route('paylabs.notify'),
            'requestId'        => uniqid('req_', true),
        ];

        // 6️⃣ Call Paylabs Service
        $service = new PaylabsQrisService();
        $result  = $service->create($payData);

        $paylabsJson = $result['paylabs']['json'] ?? null;

        /**
         * 7️⃣ VALIDASI RESPONSE PAYLABS
         * QRIS CREATE dianggap SUKSES jika:
         * - errCode = "0"
         * - qrCode ADA
         */
        if (
            !$paylabsJson ||
            ($paylabsJson['errCode'] ?? null) !== '0' ||
            empty($paylabsJson['qrCode'])
        ) {
            $transaction->update(['status' => 'failed']);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pembayaran. Silakan coba lagi.',
                'debug'   => $paylabsJson,
            ], 500);
        }

        // 8️⃣ Simpan data penting dari Paylabs
        $transaction->update([
            'platform_trade_no' => $paylabsJson['platformTradeNo'] ?? null,
            'status'            => 'pending', // QRIS selalu pending saat create
            'paylabs_response'  => $paylabsJson,
        ]);

        // 9️⃣ Response ke frontend
        return redirect()->route('payment.qris.show', [
                'transaction_code' => $transaction->transaction_code,
            ]);
    }

    public function showQris(string $transaction_code)
    {
        $transaction = Transaction::where('transaction_code', $transaction_code)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $paylabs = $transaction->paylabs_response ?? [];

        if ($transaction->status !== 'pending' || empty($paylabs['qrCode'])) {
            abort(404, 'Pembayaran tidak valid atau sudah kadaluarsa.');
        }

        return view('users.payment-qris', [
            'transaction'   => $transaction,
            'qrisUrl'       => $paylabs['qrisUrl'] ?? null,
            'expiredTime'   => $paylabs['expiredTime'] ?? null,
        ]);
    }

    public function checkStatus(string $transaction_code)
    {
        $transaction = Transaction::where('transaction_code', $transaction_code)
            ->where('user_id', Auth::id())
            ->first();

        if (! $transaction) {
            return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
        }

        return response()->json([
            'status' => $transaction->status,
            'paid'   => $transaction->status === 'success',
        ]);
    }
}
