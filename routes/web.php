<?php

use App\Http\Controllers\Ebay\EbayConnectController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('app'));

Route::prefix('ebay')->group(function () {
    Route::get('/connect', [EbayConnectController::class, 'redirect']);
    Route::get('/callback', [EbayConnectController::class, 'callback']);
});
