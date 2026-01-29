<?php

namespace App\Models;

use Database\Factories\EbayConnectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class EbayConnection extends Model
{
    /** @use HasFactory<EbayConnectionFactory> */
    use HasFactory;

    protected $fillable = [
        'provider',
        'env',
        'access_token',
        'access_token_expires_at',
        'refresh_token',
        'refresh_token_expires_at',
    ];

    protected $casts = [
        'access_token_expires_at' => 'datetime',
        'refresh_token_expires_at' => 'datetime',
    ];

    /**
     * @return HasMany<EbayInventoryItem, self>
     */
    public function inventoryItems(): HasMany
    {
        return $this->hasMany(EbayInventoryItem::class);
    }

    public function accessTokenIsExpired(): bool
    {
        /** @var Carbon|null $expiresAt */
        $expiresAt = $this->access_token_expires_at;

        if ($expiresAt === null) {
            return true;
        }

        return $expiresAt->copy()->subSeconds(30)->isPast();
    }
}
