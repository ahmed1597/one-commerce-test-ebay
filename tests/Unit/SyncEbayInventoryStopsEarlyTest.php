<?php

use App\Application\Ebay\Actions\SyncEbayInventoryAction;
use App\Domain\Ebay\Contracts\EbayInventoryGateway;
use App\Domain\Ebay\DTO\InventoryItemDTO;
use App\Infrastructure\Ebay\Repositories\EbayInventoryItemRepository;
use App\Models\EbayConnection;
use App\Models\EbayInventoryItem;

it('stops syncing when nextOffset is null even if maxPages is higher', function () {
    $conn = EbayConnection::factory()->create();

    $calls = 0;

    $gateway = new class($calls) implements EbayInventoryGateway {
        public function __construct(private int &$calls) {}

        public function getInventoryItems(string $accessToken, int $limit, int $offset): array
        {
            $this->calls++;

            return [
                'items' => [
                    new InventoryItemDTO(
                        sku: 'SKU-STOP',
                        title: 'Stop Item',
                        condition: 'NEW',
                        quantity: 1,
                        raw: ['sku' => 'SKU-STOP'],
                    ),
                ],
                'nextOffset' => null,
                'total' => 1,
            ];
        }
    };

    $action = new SyncEbayInventoryAction(
        tokens: mockTokenProvider($conn),
        gateway: $gateway,
        repo: new EbayInventoryItemRepository()
    );

    $result = $action->execute(10, 50);

    expect($result['synced'])->toBe(1);
    expect($calls)->toBe(1);
    expect(EbayInventoryItem::query()->where('sku', 'SKU-STOP')->exists())->toBeTrue();
});
