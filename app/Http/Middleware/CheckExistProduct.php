<?php

namespace App\Http\Middleware;

use App\Models\Product;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckExistProduct
{
    public function handle(Request $request, Closure $next)
    {
        $product = Product::find($request->product_id);
        if (!$product) {
            return response()->json([
                'message' => 'Sản phẩm không tồn tại'
            ], Response::HTTP_BAD_REQUEST);
        }
        return $next($request);
    }
}
