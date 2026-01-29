<?php

use App\Models\EbayConnection;
use App\Models\EbayInventoryItem;

it('returns paginated inventory via api', function () {
    $conn = EbayConnection::factory()->create();

    EbayInventoryItem::factory()->count(3)->create([
        'ebay_connection_id' => $conn->id,
    ]);

    $response = $this->getJson('/api/ebay/inventory');

    $response->assertOk()
        ->assertJsonStructure([
            'data',
            'links',
            'meta',
        ]);

    expect($response->json('data'))->toHaveCount(3);
});
