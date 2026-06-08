<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class EnsureWorkspaceOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('workspace.login')->with('error', 'يجب تسجيل الدخول أولاً.');
        }

        $user = Auth::user();

        if ($user->role !== UserRole::WORKSPACE_OWNER) {
            Auth::logout();

            return redirect()->route('workspace.login')->with('error', 'هذا الحساب ليس لديه صلاحية دخول بوابة الشركاء.');
        }

        // Check if user has an associated workspace
        if (! $user->ownedWorkspace()->exists()) {
            Auth::logout();

            return redirect()->route('workspace.login')->with('error', 'لم يتم العثور على مساحة عمل مرتبطة بهذا الحساب.');
        }

        return $next($request);
    }
}
