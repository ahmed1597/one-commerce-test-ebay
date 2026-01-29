<?php

namespace App\Application\Ebay\Actions;

use App\Domain\Ebay\Contracts\EbayTokenProvider;
use App\Domain\Ebay\Contracts\EbayInventoryGateway;
use App\Infrastructure\Ebay\Repositories\EbayInventoryItemRepository;
use Throwable;

final class SyncEbayInventoryAction
{
    public function __construct(
        private EbayTokenProvider $tokens,
        private EbayInventoryGateway $gateway,
        private EbayInventoryItemRepository $repo,
    ) {}

    /**
     * @return array{synced:int}
     */
    public function execute(int $maxPages = 2, int $limit = 50): array
    {
        $maxPages = max(1, $maxPages);
        $limit = max(1, $limit);

        $conn = $this->tokens->getValidConnectionOrFail();

        $offset = 0;
        $page = 0;
        $synced = 0;

        while ($page < $maxPages) {
            try {
                $result = $this->gateway->getInventoryItems($conn->access_token, $limit, $offset);
            } catch (Throwable $e) {
                $conn = $this->tokens->getValidConnectionOrFail();
                $result = $this->gateway->getInventoryItems($conn->access_token, $limit, $offset);
            }

            $items = $result['items'] ?? [];
            $this->repo->upsertForConnection($conn, $items);

            $synced += count($items);

            $next = $result['nextOffset'] ?? null;
            if ($next === null) {
                break;
            }

            $offset = (int) $next;
            $page++;
        }

        return ['synced' => $synced];
    }
}
