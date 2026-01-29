<?php

use App\Application\Ebay\Actions\SyncEbayInventoryAction;
use App\Domain\Ebay\Contracts\EbayInventoryGateway;
use App\Models\EbayConnection;
use App\Infrastructure\Ebay\Repositories\EbayInventoryItemRepository;

it('handles empty inventory gracefully', function () {
    $conn = EbayConnection::factory()->create();

    $gateway = new class implements EbayInventoryGateway {
        public function getInventoryItems(string $accessToken, int $limit, int $offset): array
        {
            return [
                'items' => [],
                'nextOffset' => null,
                'total' => 0,
            ];
        }
    };

    $action = new SyncEbayInventoryAction(
        tokens: mockTokenProvider($conn),
        gateway: $gateway,
        repo: new EbayInventoryItemRepository()
    );

    $result = $action->execute(50, 0);

    expect($result['synced'])->toBe(0);
});
