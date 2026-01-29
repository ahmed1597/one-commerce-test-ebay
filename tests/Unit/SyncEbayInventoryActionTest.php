<?php

use App\Application\Ebay\Actions\SyncEbayInventoryAction;
use App\Domain\Ebay\Contracts\EbayInventoryGateway;
use App\Domain\Ebay\Contracts\EbayTokenProvider;
use App\Domain\Ebay\DTO\InventoryItemDTO;
use App\Infrastructure\Ebay\Repositories\EbayInventoryItemRepository;
use App\Models\EbayConnection;
use App\Models\EbayInventoryItem;

it('syncs items using gateway and upserts into DB', function () {
    $conn = EbayConnection::factory()->create([
        'access_token' => 'token',
    ]);

    $tokens = new class($conn) implements EbayTokenProvider {
        public function __construct(private EbayConnection $conn) {}
        public function getValidConnectionOrFail(): EbayConnection
        {
            return $this->conn;
        }
    };

    $gateway = new class implements EbayInventoryGateway {
        public function getInventoryItems(string $accessToken, int $limit, int $offset): array
        {
            return [
                'items' => [
                    new InventoryItemDTO(
                        sku: 'SKU-1',
                        title: 'Title',
                        condition: 'NEW',
                        quantity: 2,
                        raw: ['sku' => 'SKU-1'],
                    ),
                ],
                'nextOffset' => null,
                'total' => 1,
            ];
        }
    };

    $repo = new EbayInventoryItemRepository();

    $action = new SyncEbayInventoryAction($tokens, $gateway, $repo);
    $result = $action->execute(2, 50);

    expect($result['synced'])->toBe(1);

    expect(
        EbayInventoryItem::query()->where('sku', 'SKU-1')->exists()
    )->toBeTrue();
});
