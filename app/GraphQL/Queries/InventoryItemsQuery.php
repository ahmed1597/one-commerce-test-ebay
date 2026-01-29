<?php

namespace App\GraphQL\Queries;

use App\Application\Ebay\Services\EbayTokenService;
use App\Domain\Ebay\Exceptions\EbayNotConnectedException;
use App\Domain\Ebay\Exceptions\EbayTokenRefreshFailedException;
use App\Models\EbayInventoryItem;

final class InventoryItemsQuery
{
    public function __construct(private EbayTokenService $tokens) {}

    /**
     * @return array<int, array<string, mixed>>
     */
    public function __invoke(): array
    {
        try {
            $conn = $this->tokens->getValidConnectionOrFail();
        } catch (EbayNotConnectedException|EbayTokenRefreshFailedException $e) {
            throw new \RuntimeException($e->getMessage());
        }

        return EbayInventoryItem::query()
            ->where('ebay_connection_id', $conn->id)
            ->orderByDesc('synced_at')
            ->limit(100)
            ->get(['id', 'sku', 'title', 'condition', 'quantity', 'synced_at'])
            ->toArray();
    }
}
