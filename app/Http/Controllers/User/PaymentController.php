<?php

namespace App\Http\Controllers\User;

use App\Models\Event;
use Xendit\Configuration;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\Registration;
use Illuminate\Http\Request;
use Xendit\Invoice\InvoiceApi;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\PaylabsService; 

class PaymentController extends Controller
{
    public function __construct()
    {
        Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));
    }

    /**
     * 1. PROSES CHECKOUT (Saat user klik Beli Tiket)
     */
    public function checkout(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $user = Auth::user();

        // Cek apakah user sudah daftar di event ini (biar ga double)
        $existing = Registration::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->where('status', '!=', 'cancelled') // Asumsi status ada active, pending, cancelled
            ->first();

        if ($existing && $existing->status == 'active') {
            return redirect()->back()->with('error', 'Anda sudah memiliki tiket untuk event ini.');
        }

        // Jika ada transaksi pending, arahkan ke link pembayaran yang sudah ada
        if ($existing && $existing->status == 'pending') {
            $lastTransaction = Transaction::where('registration_id', $existing->id)->latest()->first();
            if ($lastTransaction && $lastTransaction->payment_status == 'PENDING') {
                return redirect()->away($lastTransaction->checkout_link);
            }
        }

        try {
            DB::beginTransaction();

            // 1. Buat Data Registrasi (Pending)
            $registration = Registration::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'category_id' => $event->category_id, // Sesuaikan jika perlu
                'status' => 'pending'
            ]);

            // Hitung Harga (Harga - Diskon)
            $finalPrice = $event->price;
            if ($event->discount_percentage > 0) {
                $discountAmount = ($event->price * $event->discount_percentage) / 100;
                $finalPrice = $event->price - $discountAmount;
            }

            // Jika GRATIS, langsung aktifkan
            if ($finalPrice <= 0) {
                $registration->update(['status' => 'active']);
                DB::commit();
                return redirect()->route('user.dashboard')->with('success', 'Tiket gratis berhasil diklaim!');
            }

            // 2. Setup Xendit Invoice
            $external_id = 'TRX-' . time() . '-' . Str::random(5);
            $apiInstance = new InvoiceApi();
            $create_invoice_request = new \Xendit\Invoice\CreateInvoiceRequest([
                'external_id' => $external_id,
                'amount' => $finalPrice,
                'payer_email' => $user->email,
                'description' => 'Tiket Event: ' . $event->name,
                'invoice_duration' => 86400, // 24 Jam
                'success_redirect_url' => route('payment.success'),
                'failure_redirect_url' => route('payment.failed'),
            ]);

            // 3. Call Xendit API
            $invoice = $apiInstance->createInvoice($create_invoice_request);

            // 4. Simpan Transaksi Lokal
            Transaction::create([
                'registration_id' => $registration->id,
                'external_id' => $external_id,
                'amount' => $finalPrice,
                'payment_status' => 'PENDING',
                'checkout_link' => $invoice['invoice_url'],
                'expiry_date' => \Carbon\Carbon::parse($invoice['expiry_date'])->format('Y-m-d H:i:s')
            ]);

            DB::commit();

            // 5. Redirect User ke Xendit Payment Page
            return redirect()->away($invoice['invoice_url']);

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
            // dd($th->getMessage(), $th->getLine(), $th->getFile());
        }
    }

    /**
     * 2. WEBHOOK (Callback Otomatis dari Xendit)
     * Pastikan route ini dikecualikan dari CSRF Protection
     */
    public function callback(Request $request)
    {
        // Ambil callback token dari header (Opsional Security)
        $xenditXCallbackToken = $request->header('x-callback-token');
        // if ($xenditXCallbackToken != env('XENDIT_WEBHOOK_TOKEN')) {
        //     return response()->json(['message' => 'Unauthorized'], 401);
        // }

        $data = $request->all();

        // Cari transaksi berdasarkan external_id
        $transaction = Transaction::where('external_id', $data['external_id'])->first();

        if ($transaction) {
            if ($data['status'] == 'PAID') {
                // Update Transaksi
                $transaction->update([
                    'payment_status' => 'PAID',
                    'payment_method' => $data['payment_method'] ?? null,
                    'payment_channel' => $data['payment_channel'] ?? null,
                    'paid_at' => \Carbon\Carbon::parse($data['paid_at']),
                    'total_paid' => $data['amount']
                ]);

                // Update Status Registrasi User menjadi ACTIVE
                $transaction->registration->update(['status' => 'active']);
            } 
            elseif ($data['status'] == 'EXPIRED') {
                $transaction->update(['payment_status' => 'EXPIRED']);
                $transaction->registration->update(['status' => 'cancelled']);
            }
        }

        return response()->json(['message' => 'Success'], 200);
    }

    // Halaman Sukses (Redirect dari Xendit)
    public function success()
    {
        return redirect()->route('user.dashboard')->with('success', 'Pembayaran berhasil! Tiket sudah aktif.');
    }

    // Halaman Gagal
    public function failed()
    {
        return redirect()->route('user.dashboard')->with('error', 'Pembayaran gagal atau dibatalkan.');
    }


    // public function checkout(Request $request, $eventId, PaylabsService $paylabs)
    // {
    //     $event = Event::findOrFail($eventId);
    //     $user = Auth::user();

    //     // Cek Double Order
    //     $existing = Registration::where('user_id', $user->id)
    //         ->where('event_id', $event->id)
    //         ->where('status', '!=', 'cancelled')
    //         ->first();

    //     if ($existing && $existing->status == 'active') {
    //         return redirect()->back()->with('error', 'Anda sudah memiliki tiket ini.');
    //     }

    //     // Redirect ke link lama jika masih pending
    //     if ($existing && $existing->status == 'pending') {
    //         $lastTrx = Transaction::where('registration_id', $existing->id)->latest()->first();
    //         if ($lastTrx && $lastTrx->payment_status == 'PENDING') {
    //             return redirect()->away($lastTrx->checkout_link);
    //         }
    //     }

    //     try {
    //         DB::beginTransaction();

    //         // A. Buat Registrasi
    //         $registration = Registration::create([
    //             'user_id' => $user->id,
    //             'event_id' => $event->id,
    //             'category_id' => $event->category_id,
    //             'status' => 'pending'
    //         ]);

    //         // Hitung Harga
    //         $finalPrice = $event->price;
    //         if ($event->discount_percentage > 0) {
    //             $discount = ($event->price * $event->discount_percentage) / 100;
    //             $finalPrice = $event->price - $discount;
    //         }

    //         // Jika Gratis
    //         if ($finalPrice <= 0) {
    //             $registration->update(['status' => 'active']);
    //             DB::commit();
    //             return redirect()->route('user.dashboard')->with('success', 'Tiket gratis berhasil diklaim!');
    //         }

    //         // B. Panggil PayLabs
    //         $externalId = 'TRX-' . time() . rand(100, 999);
    //         $paylabsResponse = $paylabs->createTransaction(
    //             $externalId,
    //             (int) $finalPrice, // Pastikan integer
    //             'Tiket: ' . $event->name,
    //             $user->email,
    //             $user->name
    //         );

    //         if (!$paylabsResponse['status']) {
    //             throw new \Exception($paylabsResponse['message']);
    //         }

    //         // C. Simpan Transaksi Lokal
    //         Transaction::create([
    //             'registration_id' => $registration->id,
    //             'external_id' => $externalId,
    //             'amount' => $finalPrice,
    //             'payment_status' => 'PENDING',
    //             'checkout_link' => $paylabsResponse['checkout_link'], // URL dari PayLabs
    //             // PayLabs biasanya expired 24 jam defaultnya
    //             'expiry_date' => now()->addDay() 
    //         ]);

    //         DB::commit();

    //         // Redirect user ke halaman pembayaran PayLabs
    //         return redirect()->away($paylabsResponse['checkout_link']);

    //     } catch (\Throwable $th) {
    //         DB::rollBack();
    //         return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $th->getMessage());
    //     }
    // }

    // /**
    //  * 2. CALLBACK / WEBHOOK (Dipanggil oleh Server PayLabs)
    //  */
    // public function callback(Request $request)
    // {
    //     // PayLabs mengirim JSON body
    //     $data = $request->all();

    //     // Log untuk debugging (Cek di storage/logs/laravel.log)
    //     \Log::info('PayLabs Callback:', $data);

    //     // Ambil data penting
    //     $externalId = $data['merchant_trade_no'] ?? null;
    //     $status = $data['status'] ?? null; // 'success', 'failed', 'pending'

    //     if (!$externalId) {
    //         return response()->json(['status' => 'error', 'message' => 'No ID'], 400);
    //     }

    //     $transaction = Transaction::where('external_id', $externalId)->first();

    //     if ($transaction) {
    //         if ($status == 'success') {
    //             $transaction->update([
    //                 'payment_status' => 'PAID',
    //                 'payment_method' => $data['payment_type'] ?? 'PAYLABS',
    //                 'paid_at' => now(),
    //                 'total_paid' => $data['amount'] ?? $transaction->amount
    //             ]);
                
    //             // Aktifkan Tiket
    //             $transaction->registration->update(['status' => 'active']);
    //         } 
    //         elseif ($status == 'failed' || $status == 'expired') {
    //             $transaction->update(['payment_status' => 'FAILED']);
    //             $transaction->registration->update(['status' => 'cancelled']);
    //         }
    //     }

    //     return response()->json(['status' => 'success']);
    // }

    // public function success()
    // {
    //     return redirect()->route('user.dashboard')->with('success', 'Pembayaran berhasil! Silakan cek tiket Anda.');
    // }

    // public function failed()
    // {
    //     return redirect()->route('user.dashboard')->with('error', 'Pembayaran gagal atau dibatalkan.');
    // }
}
