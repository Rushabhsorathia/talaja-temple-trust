<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\MyDonationController;
use App\Http\Controllers\ShopController;

// Donations (guests allowed)
Route::get('/donate', [DonationController::class, 'index'])->name('donate.index');
Route::post('/donate', [DonationController::class, 'store'])->name('donate.store');
Route::post('/donate/verify', [DonationController::class, 'verify'])->name('donate.verify');
Route::post('/donate/qr', [DonationController::class, 'qr'])->name('donate.qr');

// Shop (guests allowed to browse)
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/cart', [ShopController::class, 'cart'])->name('shop.cart');
Route::post('/shop/add', [ShopController::class, 'add'])->name('shop.add');
Route::post('/shop/remove', [ShopController::class, 'remove'])->name('shop.remove');
Route::post('/shop/checkout', [ShopController::class, 'checkout'])->name('shop.checkout');

// Authenticated devotee area
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/donate/my', [MyDonationController::class, 'index'])->name('donate.my');

    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/availability', [BookingController::class, 'availability'])->name('bookings.availability');
    Route::post('/bookings/room', [BookingController::class, 'storeRoom'])->name('bookings.room');
    Route::post('/bookings/hall', [BookingController::class, 'storeHall'])->name('bookings.hall');
    Route::get('/bookings/my', [BookingController::class, 'my'])->name('bookings.my');

    Route::get('/shop/orders', [ShopController::class, 'orders'])->name('shop.orders');
});
