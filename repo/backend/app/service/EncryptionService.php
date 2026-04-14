<?php

namespace app\service;

class EncryptionService
{
    protected static string $key;

    public static function getKey(): string
    {
        if (empty(self::$key)) {
            self::$key = env('ENCRYPTION_KEY', 'default-key-change-in-production');
        }
        return self::$key;
    }

    public static function encrypt(string $plaintext): string
    {
        $key = self::getKey();
        $iv = openssl_random_pseudo_bytes(16);
        $encrypted = openssl_encrypt($plaintext, 'AES-256-CBC', $key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    public static function decrypt(string $ciphertext): string
    {
        $data = base64_decode($ciphertext);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        $key = self::getKey();
        return openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
    }

    public static function hash(string $data): string
    {
        return hash('sha256', $data);
    }

    public static function verify(string $data, string $hash): bool
    {
        return hash_equals(self::hash($data), $hash);
    }
}