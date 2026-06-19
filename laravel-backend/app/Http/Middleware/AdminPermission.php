<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class AdminPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // Existing administrators receive full access until explicit permissions are assigned.
        $permissions = $request->user('admin')?->admin_permissions ?? ['*'];
        abort_unless(in_array('*', $permissions, true) || in_array($permission, $permissions, true), 403);

        return $next($request);
    }
}
