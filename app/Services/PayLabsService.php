<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaylabsService
{
    protected $merchantId;
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->merchantId = env('PAYLABS_MERCHANT_ID');
        $this->apiKey = env('PAYLABS_API_KEY');
        $this->baseUrl = env('PAYLABS_ENV') == 'production' 
            ? env('PAYLABS_URL_PRODUCTION') 
            : env('PAYLABS_URL_SANDBOX');
    }

    /**
     * Membuat Transaksi (Request Payment URL)
     */
    public function createTransaction($refId, $amount, $productName, $customerEmail, $customerName)
    {
        // 1. Buat Signature (MD5 dari merchant_id + merchant_trade_no + amount + api_key)
        // Pastikan amount tanpa desimal untuk hashing jika PayLabs memintanya, cek dokumentasi terbaru.
        // Umumnya PayLabs v2 menggunakan format hash ini:
        $signatureRaw = $this->merchantId . $refId . $amount . $this->apiKey;
        $signature = md5($signatureRaw);

        // 2. Siapkan Payload
        $payload = [
            'merchant_id'       => $this->merchantId,
            'merchant_trade_no' => $refId,
            'amount'            => $amount,
            'product_name'      => $productName,
            'payer_email'       => $customerEmail,
            'payer_name'        => $customerName,
            'return_url'        => route('payment.success'), // Redirect setelah bayar
            'notify_url'        => route('payment.callback'), // Webhook URL (Harus public/ngrok)
            'signature'         => $signature,
            'payment_method'    => 'QRIS', // Default atau bisa dikosongkan agar user milih di hal. PayLabs
        ];

        // 3. Kirim Request
        $response = Http::post($this->baseUrl, $payload);
        $result = $response->json();

        // 4. Cek Response
        if (isset($result['success']) && $result['success'] == true) {
            return [
                'status' => true,
                'checkout_link' => $result['data']['redirect_url'], // URL Pembayaran
                'message' => 'Success'
            ];
        }

        return [
            'status' => false,
            'message' => $result['message'] ?? 'Failed to connect PayLabs'
        ];
    }
}