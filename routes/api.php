<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::get('get-all-products',[ProductController::class,'allProducts']); 
    Route::get('get-product-details/{slug}',[ProductController::class,'productDetails']); 
    Route::get('get-recommand-products',[ProductController::class,'getRecommandProducts']); 
    Route::post('place-order',[OrderController::class,'store']); 
    Route::post('order-payment-update',[OrderController::class,'orderPaymentStatus']); 
    Route::get('get-order-details/{transaction_id}',[OrderController::class,'getOrderDetails']); 
});