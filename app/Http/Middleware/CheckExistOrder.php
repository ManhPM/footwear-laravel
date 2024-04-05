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
        $order = Order::find($request->order_id);
        if (!$order) {
            return response()->json([
                'message' => 'Đơn không tồn tại'
            ], Response::HTTP_BAD_REQUEST);
        }
        return $next($request);
    }
}
