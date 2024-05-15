<?php

namespace App\Http\Middleware;

use App\Models\Cart;
use App\Models\CartProduct;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckExistCartProduct
{
    public function handle(Request $request, Closure $next)
    {
        $cart = Cart::where('user_id', auth()->user()->id)->first();
        $item = CartProduct::where('product_id', $request->product_id)->where('product_size', $request->product_size)->where('cart_id', $cart->id)->first();
        if (!$item) {
            return response()->json([
                'message' => 'Sản phẩm không tồn tại trong giỏ hàng'
            ], Response::HTTP_NOT_FOUND);
        }
        return $next($request);
    }
}
