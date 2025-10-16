<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleReviewsController;
use App\Http\Controllers\ApiController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/google-reviews', [GoogleReviewsController::class, 'getImages']);

Route::get('products', [ApiController::class, 'getProducts'])->name('api.products');
Route::get('variations', [ApiController::class, 'getVariations'])->name('api.variations');
Route::get('payment-methods', [ApiController::class, 'getPaymentMethods'])->name('api.payment-methods');
Route::get('shipping-zones', [ApiController::class, 'getShippingZones'])->name('api.shipping-zones');
