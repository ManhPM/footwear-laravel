<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CouponController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PaymentMethodController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\UploadController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\VnpayController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::post('cart_add', [CartController::class, 'store'])->middleware('check.product.detail.exist');
        Route::delete('cart_remove', [CartController::class, 'destroy'])->middleware('check.cart.product.exist');
        Route::put('cart_update', [CartController::class, 'update'])->middleware('check.cart.product.exist');
        Route::get('cart', [CartController::class, 'index']);
        Route::post('checkout', [CartController::class, 'processCheckout'])->middleware(['check.pending.order']);

        Route::get('roles/{id}', [RoleController::class, 'show']);
        Route::get('roles', [RoleController::class, 'index'])->middleware('permission:show-role');
        Route::post('roles', [RoleController::class, 'store'])->middleware('permission:create-role');
        Route::put('roles/{id}', [RoleController::class, 'update'])->middleware('permission:update-role', 'check.role.exist');
        Route::delete('roles/{id}', [RoleController::class, 'destroy'])->middleware('permission:delete-role', 'check.role.exist');

        Route::get('users/{id}', [UserController::class, 'show']);
        Route::get('users', [UserController::class, 'index'])->middleware('permission:show-user');
        Route::post('users', [UserController::class, 'store'])->middleware('permission:create-user');
        Route::put('users/{id}', [UserController::class, 'update'])->middleware('permission:update-user', 'check.user.exist');
        Route::delete('users/{id}', [UserController::class, 'destroy'])->middleware('permission:delete-user', 'check.user.exist');

        Route::get('categories/detail/{id}', [CategoryController::class, 'show']);
        Route::post('categories', [CategoryController::class, 'store'])->middleware('permission:create-category');
        Route::put('categories/{id}', [CategoryController::class, 'update'])->middleware('permission:update-category', 'check.category.exist');
        Route::delete('categories/{id}', [CategoryController::class, 'destroy'])->middleware('permission:delete-category', 'check.category.exist');

        Route::post('products', [ProductController::class, 'store'])->middleware('permission:create-product');
        Route::put('products/{id}', [ProductController::class, 'update'])->middleware('permission:update-product', 'check.product.exist');
        Route::delete('products/{id}', [ProductController::class, 'destroy'])->middleware('permission:delete-product', 'check.product.exist');

        Route::get('coupons/{id}', [CouponController::class, 'show']);
        Route::post('coupons', [CouponController::class, 'store'])->middleware('permission:create-coupon');
        Route::put('coupons/{id}', [CouponController::class, 'update'])->middleware('permission:update-coupon', 'check.coupon.exist');
        Route::delete('coupons/{id}', [CouponController::class, 'destroy'])->middleware('permission:delete-coupon', 'check.coupon.exist');

        Route::get('payment_methods', [PaymentMethodController::class, 'index']);
        Route::get('payment_methods/{id}', [PaymentMethodController::class, 'show']);
        Route::post('payment_methods', [PaymentMethodController::class, 'store']);
        Route::put('payment_methods/{id}', [PaymentMethodController::class, 'update']);
        Route::delete('payment_methods/{id}', [PaymentMethodController::class, 'destroy']);

        Route::get('/orders/confirm/{id}', [OrderController::class, 'confirmOrder'])->middleware('permission:confirm-order', 'check.order.exist');
        Route::get('/orders/cancel/{id}', [OrderController::class, 'cancelOrder'])->middleware('permission:cancel-order', 'check.order.exist');
        Route::get('/orders', [OrderController::class, 'index'])->middleware('permission:show-order');
        Route::get('/orders/detail/{id}', [OrderController::class, 'getDetailOrder'])->middleware('permission:show-order-detail', 'check.order.exist');

        Route::post('/vnpay/create_payment_url', [VnpayController::class, 'createPaymentUrl']);

        Route::get('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile', [AuthController::class, 'update']);
    });
});
Route::prefix('v1')->group(function () {
    Route::get('categories/{id}', [CategoryController::class, 'getProductsByCategoryId'])->middleware('check.category.exist');
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('coupons', [CouponController::class, 'index']);
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{id}', [ProductController::class, 'show'])->middleware('check.product.exist');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::post('/upload', [UploadController::class, 'upload']);

    Route::get('/vnpay/vnpay_return', [VnpayController::class, 'vnpayReturn']);

    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/password/reset', [AuthController::class, 'resetPassword']);
});
