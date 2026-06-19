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
        $workspace = $user->ownedWorkspace()->first();

        if ($workspace === null) {
            Auth::logout();

            return redirect()->route('workspace.login')->with('error', 'لم يتم العثور على مساحة عمل مرتبطة بهذا الحساب.');
        }

        // Only an APPROVED workspace may operate the portal. Pending registrations
        // and admin-suspended workspaces are bounced back to login with a reason.
        if (! $workspace->isApproved()) {
            Auth::logout();

            $message = $workspace->isSuspended()
                ? 'تم إيقاف مساحة العمل مؤقتًا من قبل الإدارة'
                    . ($workspace->suspension_reason ? ': ' . $workspace->suspension_reason : '.')
                : 'حسابك قيد المراجعة من قبل الإدارة. سيتم إعلامك عند الموافقة.';

            return redirect()->route('workspace.login')->with('error', $message);
        }

        return $next($request);
    }
}
