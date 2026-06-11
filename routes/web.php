<?php

use App\Http\Controllers\Account\OrderController;
use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductDescriptionImageController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductSeriesController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\StorageOptionController;
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
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order:order_code}', [OrderController::class, 'show'])->name('orders.show');
});

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function (): void {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class)->except('show');
    Route::resource('product-series', ProductSeriesController::class)
        ->parameters(['product-series' => 'productSeries'])
        ->except('show');
    Route::resource('colors', ColorController::class)->except('show');
    Route::resource('storage-options', StorageOptionController::class)
        ->parameters(['storage-options' => 'storageOption'])
        ->except('show');

    Route::post('/products/description-images', [ProductDescriptionImageController::class, 'store'])
        ->name('products.description-images.store');

    Route::post('/products/{product}/images', [ProductImageController::class, 'store'])
        ->name('products.images.store');
    Route::patch('/product-images/{image}', [ProductImageController::class, 'update'])
        ->name('product-images.update');
    Route::patch('/product-images/{image}/primary', [ProductImageController::class, 'setPrimary'])
        ->name('product-images.primary');
    Route::patch('/product-images/{image}/move', [ProductImageController::class, 'move'])
        ->name('product-images.move');
    Route::delete('/product-images/{image}', [ProductImageController::class, 'destroy'])
        ->name('product-images.destroy');

    Route::get('/products/{product}/variants', [ProductVariantController::class, 'index'])
        ->name('products.variants.index');
    Route::post('/products/{product}/variants', [ProductVariantController::class, 'store'])
        ->name('products.variants.store');
    Route::patch('/variants/{variant}', [ProductVariantController::class, 'update'])
        ->name('variants.update');
    Route::delete('/variants/{variant}', [ProductVariantController::class, 'destroy'])
        ->name('variants.destroy');

    Route::resource('products', AdminProductController::class);

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status.update');
    Route::post('/orders/{order}/cancel', [AdminOrderController::class, 'cancel'])->name('orders.cancel');

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{user}', [CustomerController::class, 'show'])->name('customers.show');
    Route::patch('/customers/{user}/status', [CustomerController::class, 'updateStatus'])->name('customers.status.update');
});
