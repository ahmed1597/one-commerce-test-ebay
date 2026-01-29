<?php

namespace App\GraphQL\Mutations;

use App\Application\Ebay\Actions\SyncEbayInventoryAction;
use App\Domain\Ebay\Exceptions\EbayNotConnectedException;
use App\Domain\Ebay\Exceptions\EbayTokenRefreshFailedException;

final class SyncEbayInventoryMutation
{
    public function __construct(private SyncEbayInventoryAction $action) {}

    /**
     * @return array{synced:int}
     */
    public function __invoke(): array
    {
        try {
            return $this->action->execute(2, 50);
        } catch (EbayNotConnectedException|EbayTokenRefreshFailedException $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }
}
