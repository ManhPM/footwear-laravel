<?php

namespace App\Http\Middleware;

use App\Models\Order;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckExistOrder
{
    public function handle(Request $request, Closure $next)
    {
        $item = Order::find($request->route('id'));
        if (!$item) {
            return response()->json([
                $request,
                'message' => 'Đơn hàng không tồn tại'
            ], Response::HTTP_NOT_FOUND);
        }
        return $next($request);
    }
}
