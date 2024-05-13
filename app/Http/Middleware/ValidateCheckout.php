<?php

namespace App\Http\Middleware;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Coupon;
use App\Models\Order;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ValidateCheckout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $item = Order::where('status', 'pending')->first();
        if ($item) {
            return response()->json([
                'data' => $item,
                'message' => 'Bạn đang có đơn chưa xác nhận không thể đặt thêm'
            ], Response::HTTP_BAD_REQUEST);
        }
        if ($request->code) {
            $coupon =  Coupon::where('name', $request['code'])->first();
            if (!$coupon) {
                return response()->json([
                    'message' => 'Mã giảm giá không tồn tại'
                ], Response::HTTP_BAD_REQUEST);
            }
        }
        return $next($request);
    }
}
