<?php

namespace App\Http\Controllers\Ebay;

use Illuminate\Http\JsonResponse;
use App\Infrastructure\Ebay\Repositories\EbayConnectionRepository;

final class EbayStatusController
{
    public function __construct(
        private EbayConnectionRepository $connections
    ) {}

    public function show(): JsonResponse
    {
        $conn = $this->connections->latest();

        return response()->json([
            'connected' => (bool) $conn,
            'env' => $conn?->env,
            'provider' => $conn?->provider,
            'connected_at' => $conn?->created_at?->toISOString(),
        ]);
    }
}
