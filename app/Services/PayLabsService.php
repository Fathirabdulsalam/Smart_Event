<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\PaylabsSignature;

class PayLabsService
{
    protected $mid;
    protected $baseUrl;

    public function __construct()
    {
        $this->mid = config('paylabs.merchant_id');
        $this->baseUrl = config('paylabs.base_url');
    }

    public function createTransaction(array $data)
    {
        try {
            $path = '/v4/payment/inquiry';
            $method = 'POST';
            $timestamp = now()->utc()->format('Y-m-d\TH:i:s\Z');
            $body = json_encode($data, JSON_UNESCAPED_SLASHES);

            $signature = PaylabsSignature::generate(
                $method,
                $path,
                $timestamp,
                $body
            );

            $response = Http::withHeaders([
                'Content-Type'  => 'application/json',
                'X-TIMESTAMP'   => $timestamp,
                'X-SIGNATURE'   => $signature,
                'X-MERCHANT-ID' => $this->mid,
            ])->post(
                $this->baseUrl . $path,
                $data
            );

            return $response->json();

        } catch (\Throwable $e) {
            Log::error('PayLabs Exception', [
                'message' => $e->getMessage()
            ]);

            return [
                'status' => false,
                'message' => 'PayLabs connection failed'
            ];
        }
    }
}
