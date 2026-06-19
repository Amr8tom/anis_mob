<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\WorkspacePortal\Actions\RegisterWorkspaceAction;
use App\Domain\WorkspacePortal\Data\WorkspaceRegistrationData;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkspacePortal\WorkspaceLoginRequest;
use App\Http\Requests\WorkspacePortal\WorkspaceRegisterRequest;
use App\Models\User;
use App\Models\WorkspaceOwnershipChange;
use App\Models\WorkspaceOwnershipInvitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

final class WorkspaceAuthController extends Controller
{
    public function showRegister(): View
    {
        return view('workspace.auth.register');
    }

    public function register(
        WorkspaceRegisterRequest $request,
        RegisterWorkspaceAction $action
    ): RedirectResponse {
        $action->handle(WorkspaceRegistrationData::fromRequest($request));

        // The workspace is held for admin review. We deliberately do NOT log the
        // owner in: a pending owner has nothing to operate, so granting a session
        // would only add attack surface. They land on a public "pending" page and
        // can sign in once an admin approves the workspace.
        return redirect()->route('workspace.pending');
    }

    public function showPending(): View
    {
        return view('workspace.auth.pending');
    }

    public function showLogin(): View
    {
        return view('workspace.auth.login');
    }

    public function showInvitation(string $token): View
    {
        $invitation = $this->validInvitation($token);

        return view('workspace.auth.accept-invitation', compact('invitation', 'token'));
    }

    public function acceptInvitation(string $token, Request $request): RedirectResponse
    {
        $invitation = $this->validInvitation($token);
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'whatsapp_number' => ['required', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8', 'max:72', 'confirmed'],
        ]);

        $owner = DB::transaction(function () use ($invitation, $validated): User {
            $owner = User::updateOrCreate(
                ['phone_number' => $invitation->phone_number],
                [
                    ...$validated,
                    'role' => UserRole::WORKSPACE_OWNER,
                    'is_guest' => false,
                ],
            );
            abort_if($owner->ownedWorkspace()->whereKeyNot($invitation->workspace_id)->exists(), 422, 'This account already owns another workspace.');

            $workspace = $invitation->workspace()->lockForUpdate()->firstOrFail();
            WorkspaceOwnershipChange::create([
                'workspace_id' => $workspace->id,
                'previous_owner_id' => $workspace->owner_id,
                'new_owner_id' => $owner->id,
                'changed_by_admin_id' => $invitation->invited_by_admin_id,
                'reason' => 'Owner accepted workspace invitation',
            ]);
            $workspace->update(['owner_id' => $owner->id]);
            $invitation->update(['accepted_at' => now()]);

            return $owner;
        });

        Auth::login($owner);

        return redirect()->route('workspace.settings.edit')->with('success', 'تم قبول الدعوة وربط مساحة العمل بحسابك.');
    }

    public function login(WorkspaceLoginRequest $request): RedirectResponse
    {
        $credentials = [
            'phone_number' => $request->string('phone_number')->trim()->value(),
            'password' => $request->string('password')->value(),
        ];

        // 1. Fetch user first to check role
        $user = User::where('phone_number', $credentials['phone_number'])->first();

        if (! $user || $user->role !== UserRole::WORKSPACE_OWNER) {
            return back()
                ->withInput($request->only('phone_number'))
                ->withErrors(['phone_number' => 'هذا الحساب غير مسجل كشريك لدينا أو البيانات المدخلة غير صحيحة.']);
        }

        // 2. Perform credential check
        if (! Auth::attempt($credentials, true)) {
            return back()
                ->withInput($request->only('phone_number'))
                ->withErrors(['password' => 'كلمة المرور غير صحيحة.']);
        }

        // 3. Enforce the workspace lifecycle before granting a usable session.
        //    (Rejected registrations are hard-deleted, so they never reach here.)
        $workspace = $user->ownedWorkspace()->first();

        if ($workspace === null || ! $workspace->isApproved()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $message = match (true) {
                $workspace === null => 'لم يتم العثور على مساحة عمل مرتبطة بهذا الحساب.',
                $workspace->isPending() => 'حسابك قيد المراجعة من قبل الإدارة. سيتم إعلامك عند الموافقة.',
                $workspace->isSuspended() => 'تم إيقاف مساحة العمل مؤقتًا من قبل الإدارة'
                    . ($workspace->suspension_reason ? ': ' . $workspace->suspension_reason : '.'),
                default => 'لا يمكن الدخول إلى هذا الحساب حاليًا.',
            };

            return back()
                ->withInput($request->only('phone_number'))
                ->withErrors(['phone_number' => $message]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('workspace.settings.edit'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('workspace.login');
    }

    private function validInvitation(string $token): WorkspaceOwnershipInvitation
    {
        return WorkspaceOwnershipInvitation::where('token_hash', hash('sha256', $token))
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->firstOrFail();
    }
}
