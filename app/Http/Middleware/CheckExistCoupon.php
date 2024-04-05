<?php

namespace App\Http\Middleware;

use App\Models\Coupon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckExistCoupon
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->code) {
            $coupon =  Coupon::where('name', $request['code'])->get();
            if ($coupon->count() == 0) {
                return response()->json([
                    'message' => 'Mã giảm giá không tồn tại'
                ], Response::HTTP_BAD_REQUEST);
            }
        }
        return $next($request);
    }
}
