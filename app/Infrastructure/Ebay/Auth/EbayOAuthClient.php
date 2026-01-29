<?php

namespace App\Infrastructure\Ebay\Auth;

use App\Infrastructure\Ebay\Http\EbayBaseUrl;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class EbayOAuthClient
{
    public function buildConsentUrl(string $state): string
    {
        $base = EbayBaseUrl::auth() . '/oauth2/authorize';

        $query = http_build_query([
            'client_id' => config('ebay.client_id'),
            'redirect_uri' => config('ebay.redirect_uri'), // RuName
            'response_type' => 'code',
            'scope' => config('ebay.scopes'),
            'state' => $state,
        ]);

        return $base . '?' . $query;
    }

    /**
     * @return array<string, mixed>
     */
    public function exchangeCodeForToken(string $code): array
    {
        $url = EbayBaseUrl::api() . '/identity/v1/oauth2/token';

        try {
            $res = Http::asForm()
                ->withBasicAuth((string) config('ebay.client_id'), (string) config('ebay.client_secret'))
                ->timeout(30)
                ->post($url, [
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'redirect_uri' => config('ebay.redirect_uri'), // RuName
                ])
                ->throw();

            /** @var array<string, mixed> $json */
            $json = $res->json();

            return $json;
        } catch (RequestException $e) {
            Log::error('eBay token exchange failed', [
                'url' => $url,
                'status' => optional($e->response)->status(),
                'body' => optional($e->response)->body(),
            ]);

            throw $e;
        }
    }
}
