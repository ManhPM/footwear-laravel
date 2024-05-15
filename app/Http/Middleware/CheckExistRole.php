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
        $item = Role::find($request->route('id'));
        if (!$item) {
            return response()->json([
                'message' => 'Role không tồn tại'
            ], Response::HTTP_NOT_FOUND);
        }
        return $next($request);
    }
}
