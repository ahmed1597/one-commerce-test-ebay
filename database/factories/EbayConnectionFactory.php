<?php

namespace Database\Factories;

use App\Models\EbayConnection;
use Illuminate\Database\Eloquent\Factories\Factory;

class EbayConnectionFactory extends Factory
{
    protected $model = EbayConnection::class;

    public function definition(): array
    {
        return [
            'provider' => 'ebay',
            'env' => config('ebay.env'),
            'access_token' => 'token',
            'access_token_expires_at' => now()->addHour(),
            'refresh_token' => 'refresh',
            'refresh_token_expires_at' => now()->addDays(30),
        ];
    }
}
