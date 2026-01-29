<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\PaylabsSignature;

class PayLabsService
{
    protected $mid;
    protected $baseUrl;
    protected $publickey;

    public function __construct()
    {
        $this->mid = config('paylabs.merchant_id');
        $this->baseUrl = config('paylabs.base_url');
        $this->publicKey = config('paylabs.public_key');
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

    public function verifyCallback(array $payload, string $signature): bool
    {
        if (!$signature || empty($payload)) {
            return false;
        }

        $stringToVerify = $payload['order_id']
            . '|' . $payload['amount']
            . '|' . $payload['status'];

        return openssl_verify(
            $stringToVerify,
            base64_decode($signature),
            $this->publicKey,
            OPENSSL_ALGO_SHA256
        ) === 1;
    }
}
