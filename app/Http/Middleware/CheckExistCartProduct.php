<?php

namespace App\Http\Middleware;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Services\MessageService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckExistCartProduct
{
    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }
    public function handle(Request $request, Closure $next)
    {

        try {
            $cart = Cart::where('user_id', auth()->user()->id)->first();
            $item = CartProduct::where('product_id', $request->product_id)->where('product_size', $request->product_size)->where('cart_id', $cart->id)->first();
            if (!$item) {
                $message = $this->messageService->getMessage('CART_PRODUCT_NOTFOUND');
                return response()->json(['message' => $message], 404);
            }
            return $next($request);
        } catch (\Throwable $th) {
            $message = $this->messageService->getMessage('INTERNAL_SERVER_ERROR');
            return response()->json(['message' => $message], 500);
        }
    }
}
