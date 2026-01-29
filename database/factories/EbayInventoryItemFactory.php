<?php

namespace Database\Factories;

use App\Models\EbayInventoryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class EbayInventoryItemFactory extends Factory
{
    protected $model = EbayInventoryItem::class;

    public function definition(): array
    {
        return [
            'ebay_connection_id' => 1,
            'sku' => 'SKU-'.$this->faker->unique()->numerify('#####'),
            'title' => $this->faker->sentence(3),
            'condition' => 'NEW',
            'quantity' => 1,
            'raw' => [],
            'synced_at' => now(),
        ];
    }
}
