<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PromotionController;
use App\Http\Controllers\Api\RecipeController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\PublicCatalogController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('dashboard')->name('api.dashboard.')->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::apiResource('promotions', PromotionController::class);
    Route::apiResource('services', ServiceController::class);
    Route::apiResource('recipes', RecipeController::class);
    Route::apiResource('videos', VideoController::class);
});

Route::prefix('catalog')->name('api.catalog.')->group(function () {
    Route::get('products', [PublicCatalogController::class, 'products'])->name('products');
    Route::get('promotions', [PublicCatalogController::class, 'promotions'])->name('promotions');
    Route::get('services', [PublicCatalogController::class, 'services'])->name('services');
});

