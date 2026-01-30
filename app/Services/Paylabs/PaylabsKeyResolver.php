<?php

namespace App\Services\Paylabs;

class PaylabsKeyResolver
{
    public static function resolvePrivateKey(): ?string
    {
        $raw = (string) \config('paylabs.private_key');
        if (trim($raw) !== '') {
            return PaylabsCrypto::normalizePrivateKey($raw);
        }

        $file = (string) \config('paylabs.private_key_file');
        if (trim($file) === '') {
            return null;
        }

        $path = self::normalizePath($file);
        if (!$path || !is_file($path)) {
            return null;
        }

        $contents = file_get_contents($path);
        if ($contents === false) {
            return null;
        }

        return PaylabsCrypto::normalizePrivateKey($contents);
    }

    public static function resolvePublicKey(): ?string
    {
        $raw = (string) \config('paylabs.public_key');
        if (trim($raw) !== '') {
            return PaylabsCrypto::normalizePublicKey($raw);
        }

        $file = (string) \config('paylabs.public_key_file');
        if (trim($file) === '') {
            return null;
        }

        $path = self::normalizePath($file);
        if (!$path || !is_file($path)) {
            return null;
        }

        $contents = file_get_contents($path);
        if ($contents === false) {
            return null;
        }

        return PaylabsCrypto::normalizePublicKey($contents);
    }

    private static function normalizePath(string $path): ?string
    {
        $path = trim($path);
        if ($path === '') {
            return null;
        }

        if (preg_match('/^[A-Za-z]:\\\\/', $path) === 1) {
            return $path;
        }

        $path = str_replace(['\\\\', '\\'], '/', $path);

        return \base_path($path);
    }
}
