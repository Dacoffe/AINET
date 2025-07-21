<?php

use App\Http\Controllers\{
    CartController,
    HomeController,
    ProductController,
    CategoryController,
    CheckoutController,
    StockController,
    ProfileController,
    SupplyOrderController
};
use App\Http\Livewire\{
    Products\CreateProduct,
    Products\EditProduct,
    Products\IndexProduct,
    Categories\EditCategory
};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

// Rotas Públicas

// Home
Route::prefix('/')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home.index');
    Route::get('/category/{category}', [HomeController::class, 'show'])->name('category.show');
    Route::get('/search', [HomeController::class, 'search'])->name('home.search');
});



Route::middleware(['auth', 'board'])->group(function () {
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}/stock', [ProductController::class, 'destroyStock'])->name('products.destroyStock');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');

    Route::patch('/supply_orders/{supply_order}/complete', [SupplyOrderController::class, 'markAsCompleted'])->name('supply_orders.complete');
});

// Mostrar produto (público)
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Checkout Routes (protected by auth middleware)
    Route::middleware('auth')->group(function () {
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('cart.checkout.index');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('cart.checkout.store');
        Route::get('/checkout/confirmation/{order}', [CheckoutController::class, 'confirmation'])->name('cart.checkout.confirmation');
        // Checkout Order Routes
        Route::get('/orders/{order}', [CheckoutController::class, 'show'])->name('cart.checkout.show');
        Route::get('/orders/{order}/pdf', [CheckoutController::class, 'generatePdf'])->name('cart.checkout.receipt');
        Route::resource('supply_orders', SupplyOrderController::class);
        Route::get('/supply_orders/{supplyOrder}', [SupplyOrderController::class, 'show'])->name('supply_orders.show');
        Route::get('/supply_orders/create', [SupplyOrderController::class, 'create'])->name('supply_orders.create');
        Route::get('/stock', [StockController::class, 'index'])->name('stock.index');

    });
});

// Rotas de Autenticação (carregar auth.php)
require __DIR__ . '/auth.php';
