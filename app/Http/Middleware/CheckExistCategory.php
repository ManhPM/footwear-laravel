<?php

namespace App\Http\Middleware;

use App\Models\Category;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckExistCategory
{
    public function handle(Request $request, Closure $next)
    {
        $item = Category::find($request->route('id'));
        if (!$item) {
            return response()->json([
                'message' => 'Sản phẩm không tồn tại'
            ], Response::HTTP_BAD_REQUEST);
        }
        return $next($request);
    }
}
