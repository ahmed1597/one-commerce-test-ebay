<?php

namespace App\Domain\Ebay\Contracts;

use App\Domain\Ebay\DTO\InventoryItemDTO;

interface EbayInventoryGateway
{
    /**
     * @return array{items: InventoryItemDTO[], nextOffset: int|null, total: int}
     */
    public function getInventoryItems(string $accessToken, int $limit, int $offset): array;
}
