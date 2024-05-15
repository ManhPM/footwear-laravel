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
        $item = Coupon::find($request->route('id'));
        if (!$item) {
            return response()->json([
                'message' => 'Mã giảm giá không tồn tại'
            ], Response::HTTP_NOT_FOUND);
        }
        return $next($request);
    }
}
