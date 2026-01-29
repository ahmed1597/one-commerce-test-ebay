<?php

namespace App\Infrastructure\Ebay\Http;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

final class EbayHttpClient
{
    public function forAccessToken(string $accessToken): PendingRequest
    {
        return Http::baseUrl(EbayBaseUrl::api())
            ->withToken($accessToken)
            ->acceptJson()
            ->timeout(20)
            ->retry(2, 200, throw: false);
    }
}
