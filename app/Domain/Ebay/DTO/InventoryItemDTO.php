<?php

namespace App\Domain\Ebay\DTO;

final readonly class InventoryItemDTO
{
    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public string $sku,
        public ?string $title,
        public ?string $condition,
        public int $quantity,
        public array $raw,
    ) {}
}
