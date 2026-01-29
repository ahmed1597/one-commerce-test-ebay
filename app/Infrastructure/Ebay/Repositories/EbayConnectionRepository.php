<?php

namespace App\Infrastructure\Ebay\Repositories;

use App\Models\EbayConnection;

final class EbayConnectionRepository
{
    public function getOrNull(): ?EbayConnection
    {
        return EbayConnection::query()
            ->where('provider', 'ebay')
            ->where('env', config('ebay.env'))
            ->latest('id')
            ->first();
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function createFromTokenPayload(array $payload): EbayConnection
    {
        $accessExpiresAt = now()->addSeconds((int) ($payload['expires_in'] ?? 0));

        $refreshExpiresAt = isset($payload['refresh_token_expires_in'])
            ? now()->addSeconds((int) $payload['refresh_token_expires_in'])
            : null;

        return EbayConnection::query()->create([
            'provider' => 'ebay',
            'env' => config('ebay.env'),
            'access_token' => (string) $payload['access_token'],
            'access_token_expires_at' => $accessExpiresAt,
            'refresh_token' => (string) ($payload['refresh_token'] ?? ''),
            'refresh_token_expires_at' => $refreshExpiresAt,
        ]);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function updateAccessToken(EbayConnection $conn, array $payload): EbayConnection
    {
        $conn->update([
            'access_token' => (string) $payload['access_token'],
            'access_token_expires_at' => now()->addSeconds((int) ($payload['expires_in'] ?? 0)),
        ]);

        return $conn->refresh();
    }

    public function latest(): ?EbayConnection
    {
        return EbayConnection::query()
            ->where('provider', 'ebay')
            ->orderByDesc('id')
            ->first();
    }
}
