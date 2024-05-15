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
        $item = Product::find($request->route('id'));
        if (!$item) {
            return response()->json([
                'message' => 'Sản phẩm không tồn tại'
            ], Response::HTTP_NOT_FOUND);
        }
        return $next($request);
    }
}
