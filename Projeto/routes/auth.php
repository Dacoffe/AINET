<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

use App\Http\Controllers\{
    AdministrativeController,
    CategoryController,
    ProductController,
    ProfileController,
    CustomerCardController,
    CardController,
    HomeController,
    DashboardController,
    OrderController
};
use Ramsey\Uuid\Codec\OrderedTimeCodec;

// Rotas para convidados (guest)
Route::middleware('guest')->group(function () {
    Volt::route('login', 'auth.login')->name('login');

    Volt::route('register', 'auth.register')->name('register');

    Volt::route('forgot-password', 'auth.forgot-password')->name('password.request');

    Volt::route('reset-password/{token}', 'auth.reset-password')->name('password.reset');
});

Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    // Rotas para boards/admins (auth + board middleware)
    Route::middleware(['auth', 'board'])->group(function () {
        Route::get('/dashboard', [AdministrativeController::class, 'index'])->name('admin.dashboard');

        Route::get('profiles', [ProfileController::class, 'index'])->name('profiles.index');
        Route::get('profiles/create', [ProfileController::class, 'create'])->name('profiles.create');
        Route::post('profiles', [ProfileController::class, 'store'])->name('profiles.store');
        Route::get('/profiles/{user}', [ProfileController::class, 'show'])->name('profiles.show');
        Route::put('profiles/{user}', [ProfileController::class, 'update'])->name('profiles.update');
        Route::delete('profiles/{user}', [ProfileController::class, 'destroy'])->name('profiles.destroy');

        // Resource para categories (se quiseres)
        Route::resource('categories', CategoryController::class);

        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    });

    Route::middleware('auth')->group(function () {
        Volt::route('verify-email', 'auth.verify-email')->name('verification.notice');

        Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        Volt::route('confirm-password', 'auth.confirm-password')->name('password.confirm');

        // Perfil
        Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
        Volt::route('settings/password', 'settings.password')->name('settings.password');
        Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

        Route::get('/my-profile', [ProfileController::class, 'show'])->name('my-profile.show');
        Route::put('/my-profile', [ProfileController::class, 'update'])->name('my-profile.update');

        // Dentro do grupo de rotas autenticadas (middleware 'auth')
        Route::put('/users/{user}/block', [ProfileController::class, 'block'])->name('users.block');
        Route::put('/users/{user}/unblock', [ProfileController::class, 'unblock'])->name('users.unblock');
        Route::put('/profiles/{user}', [ProfileController::class, 'update'])->name('profiles.update');

        Volt::route('forgot-password', 'auth.forgot-password')->name('password.change');

        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/my-orders', [OrderController::class, 'my_orders'])->name('profile.my_orders');
        Route::get('/my-orders/{order}', [OrderController::class, 'show_Order'])->name('orders.show_Order');
        Route::get('/orders/{order}/receipt', [OrderController::class, 'showReceipt'])->name('orders.receipt');

        Route::get('/orders/{order}/cancel', [OrderController::class, 'showCancelForm'])->name('orders.cancel.form');
        Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
        Route::get('/orders/{order}/receipt', [OrderController::class, 'publicReceipt'])->name('orders.public_receipt');
        Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
        Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');


        // Mostrar o cartão
        Route::get('/card', [CardController::class, 'show'])->name('card.show');
        Route::get('/card/check-balance', [CardController::class, 'checkBalance'])->name('card.checkBalance');
        Route::post('/card/refund', [CardController::class, 'refund'])->name('card.refund');
        Route::get('/card/transactions', [CardController::class, 'transactions'])->name('card.transactions');


        // Carregar saldo
        Route::post('/card/load', [CardController::class, 'load'])->name('card.load');

        // Pagar anuidade
        Route::post('/card/pay-annual-fee', [CardController::class, 'payAnnualFee'])->name('card.payAnnualFee');
    });
});
Route::prefix('employee')->group(function () {
    Route::get('/orders/pending', [OrderController::class, 'pending'])->name('orders.pending');
    Route::post('/orders/{order}/accept', [OrderController::class, 'accept'])->name('orders.accept');
});

// Rotas para utilizadores autenticados


// Logout (POST)
Route::post('logout', App\Livewire\Actions\Logout::class)->name('logout');
