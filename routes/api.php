<?php

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SupplierController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(SupplierController::class)->group(function () {
    Route::get('/supplier', 'index')->name('supplier');
    Route::post('/supplier/store', 'store')->name('supplier.store');
    Route::get('/supplier/view', 'view')->name('supplier.view');
    Route::get('/supplier/edit/{id}', 'edit')->name('supplier.edit');
    Route::post('/supplier/update/{id}', 'update')->name('supplier.update');
    Route::get('/supplier/destroy/{id}', 'destroy')->name('supplier.destroy');
    // Supplier Profiling
    // Route::get('/supplier/profile/{id}', 'SupplierProfile')->name('supplier.profile');
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(ProductsController::class)->group(function () {
    Route::get('/product', 'index')->name('product');
    Route::post('/product/store', 'store')->name('product.store');
    Route::get('/product/view', 'view')->name('product.view');
    Route::get('/product/edit/{id}', 'edit')->name('product.edit');
    Route::post('/product/update/{id}', 'update')->name('product.update');
    Route::get('/product/destroy/{id}', 'destroy')->name('product.destroy');
    // Supplier Profiling
    // Route::get('/supplier/profile/{id}', 'SupplierProfile')->name('supplier.profile');
});
