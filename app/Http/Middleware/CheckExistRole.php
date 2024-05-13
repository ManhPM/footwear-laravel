<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckExistRole
{
    public function handle(Request $request, Closure $next)
    {
        $item = Role::find($request->route('id'))->first();
        if (!$item) {
            return response()->json([
                'message' => 'Role không tồn tại'
            ], Response::HTTP_BAD_REQUEST);
        }
        return $next($request);
    }
}
