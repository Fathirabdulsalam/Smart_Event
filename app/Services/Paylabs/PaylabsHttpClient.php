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
    $server = config('paylabs.server', 'SIT');
    $merchantId = config('paylabs.merchant_id');
    $baseUrlMap = config('paylabs.base_url', []);
    $baseUrl = $baseUrlMap[$server] ?? ($baseUrlMap['SIT'] ?? null);

    if (!$baseUrl || !$merchantId) {
        throw new \RuntimeException('Paylabs config incomplete');
    }

    $privateKey = PaylabsKeyResolver::resolvePrivateKey();
    if (!$privateKey) {
        throw new \RuntimeException('Paylabs private key not found');
    }

    $bodyJson = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($bodyJson === false) {
        throw new \RuntimeException('JSON encode failed');
    }

    $hash = strtolower(hash('sha256', $bodyJson));
    $unixTimestamp = now()->timestamp; // ⚠️ UNIX TIMESTAMP
    $stringToSign = "POST:{$path}:{$hash}:{$unixTimestamp}"; // ⚠️ PATH ASLI SAJA

    $signature = PaylabsCrypto::sign($stringToSign, $privateKey);
    \Log::info('Paylabs Request', [
    'url' => rtrim($baseUrl, '/') . $path,
    'payload' => $payload,
    'headers' => [
        'X-TIMESTAMP' => (string) $unixTimestamp,
        'X-SIGNATURE' => $signature,
        'X-PARTNER-ID' => $merchantId,
    ],
    'string_to_sign' => "POST:{$path}:{$hash}:{$unixTimestamp}",
]);
    $response = Http::timeout(30)
        ->withHeaders([
            'Content-Type' => 'application/json',
            'X-TIMESTAMP' => (string) $unixTimestamp, // ⚠️ KIRIM JUGA DALAM HEADER
            'X-SIGNATURE' => $signature,
            'X-PARTNER-ID' => $merchantId,
            'X-REQUEST-ID' => $requestId ?: $this->requestId(),
        ])
        ->post(rtrim($baseUrl, '/') . $path, $payload); // ⚠️ BASE URL SUDAH TERMASUK /payment/v2.3 ?
    
    return [
        'ok' => $response->successful(),
        'status' => $response->status(),
        'json' => $response->json(),
        'raw' => $response->body(),
        'headers' => [
            'X-TIMESTAMP' => (string) $unixTimestamp,
            'X-SIGNATURE' => $signature,
            'X-PARTNER-ID' => $merchantId,
            'X-REQUEST-ID' => $requestId ?: $this->requestId(),
        ],
    ];
}
}
