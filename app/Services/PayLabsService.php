<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayLabsService
{
    protected $mid;
    protected $baseUrl;

    public function __construct()
    {
        $this->mid = env('PAYLABS_MID');
        $this->baseUrl = env('PAYLABS_API_URL');
    }

    public function createTransaction($data)
    {
        try {
            $response = Http::withHeaders([
                'MID' => $this->mid,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/api/v1/transaction", $data);

            $result = $response->json();

            if ($response->successful() && isset($result['data']['payment_url'])) {
                return [
                    'status' => true,
                    'checkout_link' => $result['data']['payment_url'],
                    'message' => 'Success'
                ];
            }

            Log::error('PayLabs Error', $result);
            return [
                'status' => false,
                'message' => $result['message'] ?? 'Gagal membuat transaksi'
            ];

        } catch (\Exception $e) {
            Log::error('PayLabs Exception', ['message' => $e->getMessage()]);
            return [
                'status' => false,
                'message' => 'Koneksi ke PayLabs gagal'
            ];
        }
    }
}