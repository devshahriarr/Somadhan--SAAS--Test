<?php

use App\Http\Controllers\Sale\SalePageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::controller(SalePageController::class)->group(function () {
        Route::get('/sale-page', 'index')->name('sale.page');
    });
});
