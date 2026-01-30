<?php

namespace App\Services\Paylabs;

class PaylabsCrypto
{
    public static function normalizePrivateKey(?string $key): ?string
    {
        if (!$key) {
            return null;
        }

        $key = str_replace(["\r\n", "\n", "\r"], "\n", $key);
        $key = str_replace("\\n", "\n", $key);
        $key = trim($key);

        if (str_contains($key, 'BEGIN')) {
            return $key;
        }

        return "-----BEGIN RSA PRIVATE KEY-----\n{$key}\n-----END RSA PRIVATE KEY-----";
    }

    public static function normalizePublicKey(?string $key): ?string
    {
        if (!$key) {
            return null;
        }

        $key = str_replace(["\r\n", "\n", "\r"], "\n", $key);
        $key = str_replace("\\n", "\n", $key);
        $key = trim($key);

        if (str_contains($key, 'BEGIN')) {
            return $key;
        }

        return "-----BEGIN PUBLIC KEY-----\n{$key}\n-----END PUBLIC KEY-----";
    }

    public static function sign(string $data, string $privateKey): string
    {
        $binarySignature = '';
        $ok = openssl_sign($data, $binarySignature, $privateKey, OPENSSL_ALGO_SHA256);

        if ($ok !== true) {
            throw new \RuntimeException('Failed to sign payload');
        }

        return base64_encode($binarySignature);
    }

    public static function verify(string $data, string $signatureBase64, string $publicKey): bool
    {
        $binarySignature = base64_decode($signatureBase64, true);
        if ($binarySignature === false) {
            return false;
        }

        return openssl_verify($data, $binarySignature, $publicKey, OPENSSL_ALGO_SHA256) === 1;
    }
}
