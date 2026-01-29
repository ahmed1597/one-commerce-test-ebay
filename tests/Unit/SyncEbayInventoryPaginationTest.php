<?php

use App\Application\Ebay\Actions\SyncEbayInventoryAction;
use App\Domain\Ebay\Contracts\EbayInventoryGateway;
use App\Domain\Ebay\DTO\InventoryItemDTO;
use App\Models\EbayConnection;
use App\Models\EbayInventoryItem;
use App\Infrastructure\Ebay\Repositories\EbayInventoryItemRepository;

it('syncs inventory across multiple pages', function () {
    $conn = EbayConnection::factory()->create();

    $gateway = new class implements EbayInventoryGateway {
        private int $call = 0;

        public function getInventoryItems(string $accessToken, int $limit, int $offset): array
        {
            $this->call++;

            if ($this->call === 1) {
                return [
                    'items' => [
                        new InventoryItemDTO('SKU-1', 'Item 1', 'NEW', 5, []),
                    ],
                    'nextOffset' => 1,
                    'total' => 2,
                ];
            }

            return [
                'items' => [
                    new InventoryItemDTO('SKU-2', 'Item 2', 'NEW', 3, []),
                ],
                'nextOffset' => null,
                'total' => 2,
            ];
        }
    };

    $action = new SyncEbayInventoryAction(
        mockTokenProvider($conn),
        $gateway,
        new EbayInventoryItemRepository()
    );

    $result = $action->execute(2, 1);

    expect($result['synced'])->toBe(2);
    expect(EbayInventoryItem::count())->toBe(2);
});
