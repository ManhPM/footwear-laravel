<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class Authorize
{
    public function handle(Request $request, Closure $next, $role)
    {
        $authGuard = Auth::guard('sanctum');
        $user = $authGuard->user();
        if (!Auth::check() || !Auth::user()->hasRole($role)) {
            return response()->json([
                'message' => 'Bạn không có quyền thực hiện chức năng này'
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
