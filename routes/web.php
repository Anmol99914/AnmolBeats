<?php

use App\Http\Controllers\BeatController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/check-auth', function () {
    if (Auth::check()) {
        return response()->json(['logged_in' => true]);
    }
    return response()->json(['logged_in' => false]);
});

// Public routes - anyone can access
Route::get('/', [BeatController::class, 'index'])->name('home');
Route::get('/beats', [BeatController::class, 'index'])->name('beats.index');
Route::get('/beats/{beat}', [BeatController::class, 'show'])->name('beats.show');

// Cart routes - anyone can add to cart (cart stored in session)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{beat}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');

// Protected routes - need login
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/orders', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/order/{order}/confirmation', [OrderController::class, 'confirmation'])->name('order.confirmation');
    Route::get('/checkout/stripe', [CheckoutController::class, 'stripe'])->name('checkout.stripe');
    Route::post('/checkout/stripe/process', [CheckoutController::class, 'stripeProcess'])->name('checkout.stripe.process');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('beats', App\Http\Controllers\Admin\BeatController::class);
    Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status/{status}', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
});

Route::get('/dashboard', function () {
    return redirect('/');
})->name('dashboard');

Route::get('/checkout/stripe/success', function () {
    return redirect()->route('orders.history');
})->name('checkout.stripe.success');

require __DIR__.'/auth.php';