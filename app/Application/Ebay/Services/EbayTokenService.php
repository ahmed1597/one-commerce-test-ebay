<?php

namespace App\Application\Ebay\Services;

use Throwable;
use App\Models\EbayConnection;
use App\Domain\Ebay\Contracts\EbayTokenProvider;
use App\Infrastructure\Ebay\Auth\EbayOAuthClient;
use App\Domain\Ebay\Exceptions\EbayNotConnectedException;
use App\Domain\Ebay\Exceptions\EbayTokenRefreshFailedException;
use App\Infrastructure\Ebay\Repositories\EbayConnectionRepository;

final class EbayTokenService implements EbayTokenProvider
{   
    public function __construct(
        private EbayConnectionRepository $connections,
        private EbayOAuthClient $oauth,
    ) {}

    public function getValidConnectionOrFail(): EbayConnection
    {
        $conn = $this->connections->getOrNull();

        if (! $conn) {
            throw EbayNotConnectedException::make();
        }

        if (! $conn->accessTokenIsExpired()) {
            return $conn;
        }

        try {
            $payload = $this->oauth->refreshUserAccessToken($conn->refresh_token);

            return $this->connections->updateAccessToken($conn, $payload);
        } catch (Throwable) {
            throw EbayTokenRefreshFailedException::make();
        }
    }
}
