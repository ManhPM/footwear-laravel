<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use App\Models\RoleHasPermission;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class Authorize
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = auth()->user();

        $rolesId = $user->roles->pluck('id')->toArray();

        $permissions = RoleHasPermission::where('role_id', $rolesId)->get();

        $permissionsName = [];
        foreach ($permissions as $item) {
            $permission = Permission::where('id', $item->permission_id)->first();
            if ($permission) {
                $permissionsName[] = $permission->name;
            }
        }

        if (!in_array($permission->name, $permissionsName)) {
            return response()->json([
                'message' => 'Bạn không có quyền sử dụng chức năng này'
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
