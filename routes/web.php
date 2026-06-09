<?php

use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

Route::prefix('cart')->name('cart.')->group(function (): void {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::get('/summary', [CartController::class, 'summary'])->name('summary');
    Route::post('/items', [CartController::class, 'store'])->name('items.store');
    Route::patch('/items/{variant}', [CartController::class, 'update'])->name('items.update');
    Route::delete('/items/{variant}', [CartController::class, 'destroyItem'])->name('items.destroy');
    Route::delete('/', [CartController::class, 'destroy'])->name('destroy');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::prefix('checkout')->name('checkout.')->middleware(['auth', 'active'])->group(function (): void {
    Route::get('/', [CheckoutController::class, 'create'])->name('create');
    Route::get('/summary', [CheckoutController::class, 'summary'])->name('summary');
    Route::post('/', [CheckoutController::class, 'store'])->name('store');
    Route::get('/success/{order:order_code}', [CheckoutController::class, 'success'])->name('success');
});

Route::prefix('account')->name('account.')->middleware(['auth', 'active'])->group(function (): void {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
});

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function (): void {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});
