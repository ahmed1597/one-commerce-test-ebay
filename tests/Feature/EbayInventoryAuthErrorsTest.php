<?php

use App\Application\Ebay\Services\EbayTokenService;
use App\Domain\Ebay\Contracts\EbayTokenProvider;
use App\Domain\Ebay\Exceptions\EbayNotConnectedException;
use App\Domain\Ebay\Exceptions\EbayTokenRefreshFailedException;

it('returns 409 when ebay is not connected', function () {
    $this->mock(EbayTokenProvider::class, function ($mock) {
        $mock->shouldReceive('getValidConnectionOrFail')
            ->andThrow(EbayNotConnectedException::make());
    });

    $this->getJson('/api/ebay/inventory')
        ->assertStatus(409)
        ->assertJsonStructure(['message']);
});

it('returns 409 when token refresh fails', function () {
    $this->mock(EbayTokenProvider::class, function ($mock) {
        $mock->shouldReceive('getValidConnectionOrFail')
            ->andThrow(EbayTokenRefreshFailedException::make());
    });

    $this->getJson('/api/ebay/inventory')
        ->assertStatus(409)
        ->assertJsonStructure(['message']);
});


