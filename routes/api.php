<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CouponController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::post('cart-add', [CartController::class, 'store'])->middleware('check.product.exist');
        Route::delete('cart-remove', [CartController::class, 'destroy'])->middleware('check.product.exist');
        Route::put('cart-update', [CartController::class, 'update'])->middleware('check.product.exist');
        Route::get('cart', [CartController::class, 'index']);
        Route::post('checkout', [CartController::class, 'processCheckout'])->middleware(['check.coupon.exist', 'check.pending.order']);

        Route::resource('roles', RoleController::class);
        Route::resource('users', UserController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::resource('coupons', CouponController::class);

        Route::get('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile', [AuthController::class, 'update']);
    });
});
Route::prefix('v1')->group(function () {
    Route::get('categories/{category_id}', [CategoryController::class, 'getProductsByCategoryId']);
    Route::get('products/{product_id}', [ProductController::class, 'show']);
    Route::get('products/{product_id}', [AuthController::class, 'show']);
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->cookie();
    });
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::post('/forgotpassword', [AuthController::class, 'forgotPassword']);
    Route::post('/password/reset', [AuthController::class, 'resetPassword']);
});
