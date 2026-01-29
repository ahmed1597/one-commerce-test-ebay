<?php

namespace App\Infrastructure\Ebay\Http;

use App\Domain\Ebay\Contracts\EbayInventoryGateway;
use App\Domain\Ebay\DTO\InventoryItemDTO;

final class EbayInventoryApi implements EbayInventoryGateway
{
    public function __construct(private EbayHttpClient $client) {}

    public function getInventoryItems(string $accessToken, int $limit, int $offset): array
    {
        $response = $this->client->forAccessToken($accessToken)
            ->get('/sell/inventory/v1/inventory_item', [
                'limit' => $limit,
                'offset' => $offset,
            ]);

        if ($response->status() === 401) {
            // token invalid/expired
            $response->throw();
        }

        if ($response->status() === 429) {
            // rate limited
            abort(429, 'Rate limited by eBay. Please retry shortly.');
        }

        $response->throw();

        $json = $response->json();

        $items = [];
        foreach (($json['inventoryItems'] ?? []) as $row) {
            $sku = (string) ($row['sku'] ?? '');

            if ($sku === '') {
                // ignore malformed entries rather than crashing sync.
                continue;
            }

            $qty = (int) data_get($row, 'availability.shipToLocationAvailability.quantity', 0);

            $items[] = new InventoryItemDTO(
                sku: $sku,
                title: data_get($row, 'product.title'),
                condition: $row['condition'] ?? null,
                quantity: max(0, $qty),
                raw: $row,
            );
        }

        $total = (int) ($json['total'] ?? 0);
        $nextOffset = ($offset + $limit) < $total ? ($offset + $limit) : null;

        return ['items' => $items, 'nextOffset' => $nextOffset, 'total' => $total];
    }
}
