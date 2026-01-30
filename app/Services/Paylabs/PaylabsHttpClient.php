<?php

namespace App\Services\Paylabs;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaylabsHttpClient
{
    public function timestamp(): string
    {
        return Carbon::now('Asia/Jakarta')->format('Y-m-d\TH:i:s.vP');
    }

    public function requestId(): string
    {
        return Carbon::now('Asia/Jakarta')->format('YmdHis') . random_int(10000, 99999);
    }

    public function merchantTradeNo(): string
    {
        return 'TRX-' . Carbon::now('Asia/Jakarta')->format('YmdHis') . '-' . Str::upper(Str::random(6));
    }

    public function post(string $path, array $payload, ?string $requestId = null): array
    {
        $server = \config('paylabs.server', 'SIT');
        $version = \config('paylabs.version', 'v2.3');
        $merchantId = \config('paylabs.merchant_id');
        $baseUrlMap = \config('paylabs.base_url', []);
        $baseUrl = $baseUrlMap[$server] ?? ($baseUrlMap['SIT'] ?? null);

        if (!$baseUrl) {
            throw new \RuntimeException(
                "Paylabs base URL is not configured (server={$server}). " .
                'Set PAYLABS_BASE_URL_SIT (or PAYLABS_BASE_URL) in .env and clear config cache.'
            );
        }
        if (!$merchantId) {
            throw new \RuntimeException(
                'Paylabs merchant id is not configured. Set PAYLABS_MERCHANT_ID in .env and clear config cache.'
            );
        }

        $timestamp = $this->timestamp();
        $headerRequestId = $requestId ?: $this->requestId();

        $privateKey = PaylabsKeyResolver::resolvePrivateKey();
        if (!$privateKey) {
            throw new \RuntimeException('Paylabs private key is not configured');
        }

        $bodyJson = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($bodyJson === false) {
            throw new \RuntimeException('Failed to JSON-encode request payload');
        }

        $hash = strtolower(hash('sha256', $bodyJson));
        $stringToSign = "POST:/payment/{$version}{$path}:{$hash}:{$timestamp}";
        $signature = PaylabsCrypto::sign($stringToSign, $privateKey);

        $url = rtrim($baseUrl, '/') . "/payment/{$version}{$path}";

        $response = Http::timeout(30)
            ->withHeaders([
                'Content-Type' => 'application/json;charset=utf-8',
                'X-TIMESTAMP' => $timestamp,
                'X-SIGNATURE' => $signature,
                'X-PARTNER-ID' => $merchantId,
                'X-REQUEST-ID' => $headerRequestId,
            ])
            ->withBody($bodyJson, 'application/json')
            ->post($url);

        return [
            'ok' => $response->successful(),
            'status' => $response->status(),
            'json' => $response->json(),
            'raw' => $response->body(),
            'headers' => [
                'X-TIMESTAMP' => $timestamp,
                'X-SIGNATURE' => $signature,
                'X-PARTNER-ID' => $merchantId,
                'X-REQUEST-ID' => $headerRequestId,
            ],
        ];
    }
}
