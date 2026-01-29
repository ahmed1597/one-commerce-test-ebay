<?php

use App\Application\Ebay\Services\EbayTokenService;
use App\Domain\Ebay\Exceptions\EbayNotConnectedException;
use App\Models\EbayConnection;

it('throws exception when no ebay connection exists', function () {
    EbayConnection::query()->delete();

    $service = app(EbayTokenService::class);

    $service->getValidConnectionOrFail();
})->throws(EbayNotConnectedException::class);
