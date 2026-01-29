<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Ebay\EbayStatusController;
use App\Http\Controllers\Ebay\EbayInventoryController;

Route::prefix('ebay')->group(function () {
    Route::get('/inventory', [EbayInventoryController::class, 'index']);        
    Route::get('/inventory-items', [EbayInventoryController::class, 'index']);  
    Route::post('/sync', [EbayInventoryController::class, 'sync']);
    Route::get('/status', [EbayStatusController::class, 'show']);
});
