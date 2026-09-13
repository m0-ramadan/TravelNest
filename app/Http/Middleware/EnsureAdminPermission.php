<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $admin = $request->user('admin');

        abort_unless($admin, Response::HTTP_FORBIDDEN);

        if ($admin->isSuperAdmin()) {
            return $next($request);
        }

        try {
            $allowed = $admin->hasPermissionTo($permission);
        } catch (\Spatie\Permission\Exceptions\PermissionDoesNotExist) {
            $allowed = false;
        }

        abort_unless($allowed, Response::HTTP_FORBIDDEN);

        return $next($request);
    }
}