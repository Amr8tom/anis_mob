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
        $guard = Auth::guard('workspace_owner');

        if (! $guard->check()) {
            $legacyGuard = Auth::guard('web');
            $legacyUser = $legacyGuard->user();

            if ($legacyUser !== null && $legacyUser->role === UserRole::WORKSPACE_OWNER) {
                Auth::shouldUse('web');
                $workspace = $legacyUser->ownedWorkspace()->first();

                return $this->continueIfWorkspaceIsAllowed($request, $next, $workspace, $legacyGuard);
            }

            return redirect()->route('workspace.login')->with('error', 'يجب تسجيل الدخول أولاً.');
        }

        Auth::shouldUse('workspace_owner');

        // Check if user has an associated workspace
        $workspace = $guard->user()->ownedWorkspace()->first();

        return $this->continueIfWorkspaceIsAllowed($request, $next, $workspace, $guard);
    }

    private function continueIfWorkspaceIsAllowed(Request $request, Closure $next, mixed $workspace, mixed $guard): Response
    {
        if ($workspace === null) {
            $guard->logout();

            return redirect()->route('workspace.login')->with('error', 'لم يتم العثور على مساحة عمل مرتبطة بهذا الحساب.');
        }

        // Only an APPROVED workspace may operate the portal. Pending registrations
        // and admin-suspended workspaces are bounced back to login with a reason.
        if (! $workspace->isApproved()) {
            $guard->logout();

            $message = $workspace->isSuspended()
                ? 'تم إيقاف مساحة العمل مؤقتًا من قبل الإدارة'
                    . ($workspace->suspension_reason ? ': ' . $workspace->suspension_reason : '.')
                : 'حسابك قيد المراجعة من قبل الإدارة. سيتم إعلامك عند الموافقة.';

            return redirect()->route('workspace.login')->with('error', $message);
        }

        return $next($request);
    }
}
