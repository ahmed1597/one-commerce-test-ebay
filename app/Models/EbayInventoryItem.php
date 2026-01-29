<?php

namespace App\Models;

use Database\Factories\EbayInventoryItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EbayInventoryItem extends Model
{
    /** @use HasFactory<EbayInventoryItemFactory> */
    use HasFactory;

    protected $fillable = [
        'ebay_connection_id',
        'sku',
        'title',
        'condition',
        'quantity',
        'raw',
        'synced_at',
    ];

    protected $casts = [
        'raw' => 'array',
        'synced_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<EbayConnection, self>
     */
    public function connection(): BelongsTo
    {
        return $this->belongsTo(EbayConnection::class, 'ebay_connection_id');
    }
}
