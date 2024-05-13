<?php

namespace App\Http\Middleware;

use App\Models\ProductDetail;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckExistProductDetail
{
    public function handle(Request $request, Closure $next)
    {
        $item = ProductDetail::where('product_id', $request->product_id)->where('size', $request->product_size)->first();
        if (!$item) {
            return response()->json([
                'message' => 'Sản phẩm không tồn tại'
            ], Response::HTTP_BAD_REQUEST);
        }
        return $next($request);
    }
}
