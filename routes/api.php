<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DiscountController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthenticatedSessionController::class, 'loginThroughApp']);

Route::get('/categories', [CategoryController::class, 'getCategories']);
Route::get('/category/{categoryId}/products', [ProductController::class, 'getProductsByCategory']);
Route::get('/products/search', [ProductController::class, 'searchProductsByName']);
// Route::get('/products/barcode', [ProductController::class, 'getProductsByBarcodeId']);
Route::get('product/barcode/{barcode_id}', [ProductController::class, 'getProductByBarcode']);
Route::get('ads', [DiscountController::class, 'getAds']);
Route::post('/create/payment-intent', [OrderController::class, 'createPaymentIntent']);
Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders/{email}', [OrderController::class, 'getUserOrders']);
Route::get('/products', [OrderController::class, 'getProducts']);
Route::post('/create/payment-intent-amount', [OrderController::class, 'createPaymentIntentAmount']);
