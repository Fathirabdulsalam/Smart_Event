<?php

namespace App\Http\Controllers\User;

use App\Models\Event;
use App\Models\Transaction;
use App\Services\PayLabsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    protected $payLabs;

    public function __construct(PayLabsService $payLabs)
    {
        $this->payLabs = $payLabs;
    }

    /**
     * Proses Checkout (PayLabs)
     */
    public function checkout(Request $request, $eventId)
    {
        $event = Event::with('tickets')->findOrFail($eventId);
        $user = Auth::user();

        // Ambil tiket pertama (atau tambah logika pilih tiket)
        $ticket = $event->tickets->first();
        if (!$ticket) {
            return back()->with('error', 'Tiket tidak tersedia untuk event ini.');
        }

        // Cek apakah user sudah punya transaksi aktif
        $existing = Transaction::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->where('status', 'success')
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah memiliki tiket untuk event ini.');
        }

        try {
            DB::beginTransaction();

            // Generate kode transaksi
            $transactionCode = Transaction::generateCode();

            // Hitung harga akhir
            $finalPrice = $ticket->price;
            if ($event->discount_percentage > 0) {
                $discount = ($ticket->price * $event->discount_percentage) / 100;
                $finalPrice = $ticket->price - $discount;
            }

            // Jika gratis
            if ($finalPrice <= 0) {
                Transaction::create([
                    'transaction_code' => $transactionCode,
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                    'ticket_id' => $ticket->id,
                    'quantity' => 1,
                    'total_amount' => 0,
                    'status' => 'success',
                    'paid_at' => now()
                ]);
                DB::commit();
                return redirect()->route('user.dashboard')->with('success', 'Tiket gratis berhasil diklaim!');
            }

            // Kirim ke PayLabs
            $orderData = [
                'order_id' => $transactionCode,
                'amount' => (int) $finalPrice,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $user->phone ?? '08123456789',
                'return_url' => route('payment.return', ['trx' => $transactionCode]),
                'callback_url' => route('payment.callback'),
                'items' => [
                    [
                        'name' => $ticket->name,
                        'price' => (int) $finalPrice,
                        'quantity' => 1,
                    ]
                ],
            ];

            $result = $this->payLabs->createTransaction($orderData);

            if (!$result['status']) {
                throw new \Exception($result['message']);
            }

            // Simpan transaksi
            Transaction::create([
                'transaction_code' => $transactionCode,
                'user_id' => $user->id,
                'event_id' => $event->id,
                'ticket_id' => $ticket->id,
                'quantity' => 1,
                'total_amount' => $finalPrice,
                'status' => 'pending',
                'payment_url' => $result['checkout_link'],
                'paylabs_response' => $result
            ]);

            DB::commit();

            return redirect($result['checkout_link']);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Redirect setelah pembayaran (dari PayLabs)
     */
    public function return(Request $request)
    {
        $trxCode = $request->get('trx');
        $transaction = Transaction::where('transaction_code', $trxCode)->first();

        if ($transaction && $transaction->status === 'pending') {
            // Untuk MVP, anggap redirect = sukses
            $transaction->update(['status' => 'success', 'paid_at' => now()]);
            return view('users.payment.success', compact('transaction'));
        }

        return view('users.payment.failed');
    }

    /**
     * Webhook dari PayLabs (opsional untuk verifikasi real-time)
     */
    public function callback(Request $request)
    {
        // TODO: Verifikasi signature & update status real-time
        // Untuk sekarang, abaikan dulu
        return response()->json(['status' => 'ok']);
    }

    /**
     * Riwayat Pembelian
     */
    public function history()
    {
        $transactions = Transaction::with(['event', 'ticket'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('users.payment.history', compact('transactions'));
    }

    /**
     * Tampilkan Tiket
     */
    public function showTicket($id)
    {
        $transaction = Transaction::with(['event', 'ticket'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        if ($transaction->status !== 'success') {
            abort(403, 'Tiket tidak valid.');
        }

        return view('users.payment.ticket', compact('transaction'));
    }
}