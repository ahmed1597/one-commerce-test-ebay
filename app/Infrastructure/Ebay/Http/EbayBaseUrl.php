<?php

namespace App\Infrastructure\Ebay\Http;

final class EbayBaseUrl
{
    public static function env(): string
    {
        return (string) config('ebay.env', 'sandbox');
    }

    public static function isSandbox(): bool
    {
        return self::env() === 'sandbox';
    }

    public static function auth(): string
    {
        return self::isSandbox()
            ? 'https://auth.sandbox.ebay.com'
            : 'https://auth.ebay.com';
    }

    public static function api(): string
    {
        return self::isSandbox()
            ? 'https://api.sandbox.ebay.com'
            : 'https://api.ebay.com';
    }
}
