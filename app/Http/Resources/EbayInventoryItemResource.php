<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class EbayInventoryItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'sku' => (string) $this->sku,
            'title' => (string) $this->title,
            'condition' => (string) $this->condition,
            'quantity' => (int) $this->quantity,
            'synced_at' => optional($this->synced_at)->toISOString(),
        ];
    }
}
