<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DiscountController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    $totalCategories = Category::count();
    $totalProducts = Product::count();
    $outOfStockProducts = Product::where('quantity', 0)->count();
    $totalOrders = 13;
    return view('dashboard', [
        "totalCategories" => $totalCategories, 
        "totalProducts" => $totalProducts, 
        "outOfStockProducts" => $outOfStockProducts, 
        "totalOrders" => $totalOrders, 
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('categories', CategoryController::class);
Route::resource('products', ProductController::class);
Route::resource('orders', OrderController::class);
Route::put('/orders/{id}/status', [OrderController::class, 'updateOrderStatus'])
->name('updateOrderStatus');

Route::resource('discounts', DiscountController::class);

require __DIR__.'/auth.php';
