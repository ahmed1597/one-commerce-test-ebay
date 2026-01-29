<?php

namespace App\Infrastructure\Ebay\Repositories;

use App\Domain\Ebay\DTO\InventoryItemDTO;
use App\Models\EbayConnection;
use App\Models\EbayInventoryItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class EbayInventoryItemRepository
{
    /**
     * @param  InventoryItemDTO[]  $items
     */
    public function upsertForConnection(EbayConnection $conn, array $items): void
    {
        if ($items === []) {
            return;
        }

        $now = now();

        $payload = array_map(static function (InventoryItemDTO $dto) use ($conn, $now): array {
            $sku = is_string($dto->sku) ? $dto->sku : (string) ($dto->sku ?? '');

            $raw = $dto->raw;
            if (is_array($raw)) {
                $raw = json_encode($raw, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            } elseif (is_object($raw)) {
                $raw = json_encode((array) $raw, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            } elseif (! is_string($raw)) {
                $raw = json_encode(['value' => $raw], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }

            return [
                'ebay_connection_id' => $conn->id,
                'sku' => $sku,
                'title' => (string) ($dto->title ?? ''),
                'condition' => (string) ($dto->condition ?? ''),
                'quantity' => (int) ($dto->quantity ?? 0),
                'raw' => $raw,
                'synced_at' => $now,
                'updated_at' => $now,
                'created_at' => $now,
            ];
        }, $items);

        EbayInventoryItem::query()->upsert(
            $payload,
            ['ebay_connection_id', 'sku'],
            ['title', 'condition', 'quantity', 'raw', 'synced_at', 'updated_at']
        );
    }

    /**
     * @return LengthAwarePaginator<int, EbayInventoryItem>
     */
    public function paginateForConnection(EbayConnection $conn, int $perPage = 25): LengthAwarePaginator
    {
        return EbayInventoryItem::query()
            ->where('ebay_connection_id', $conn->id)
            ->orderByDesc('synced_at')
            ->paginate($perPage);
    }
}
