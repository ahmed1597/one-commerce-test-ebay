<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Ebay\Contracts\EbayTokenProvider;
use App\Infrastructure\Ebay\Http\EbayInventoryApi;
use App\Application\Ebay\Services\EbayTokenService;
use App\Domain\Ebay\Contracts\EbayInventoryGateway;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EbayTokenProvider::class, EbayTokenService::class);

        $this->app->bind(EbayInventoryGateway::class, EbayInventoryApi::class);    }

    public function boot(): void {}
}
