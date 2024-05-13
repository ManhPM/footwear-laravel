<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckExistUser
{
    public function handle(Request $request, Closure $next)
    {
        $item = User::find($request->route('id'));
        if (!$item) {
            return response()->json([
                'message' => 'Người dùng không tồn tại'
            ], Response::HTTP_BAD_REQUEST);
        }
        return $next($request);
    }
}
