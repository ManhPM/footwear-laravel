<?php

namespace App\Http\Middleware;

use App\Models\Coupon;
use App\Models\PaymentMethod;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckExistPaymentMethod
{
    public function handle(Request $request, Closure $next)
    {
        $item = PaymentMethod::find($request->route('id'));
        if (!$item) {
            return response()->json([
                'message' => 'Phương thức thanh toán không tồn tại'
            ], Response::HTTP_NOT_FOUND);
        }
        return $next($request);
    }
}
