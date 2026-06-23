<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\WorkspacePortal\Actions\RegisterWorkspaceAction;
use App\Domain\WorkspacePortal\Data\WorkspaceRegistrationData;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkspacePortal\WorkspaceLoginRequest;
use App\Http\Requests\WorkspacePortal\WorkspaceRegisterRequest;
use App\Models\WorkspaceOwner;
use App\Models\WorkspaceOwnershipChange;
use App\Models\WorkspaceOwnershipInvitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

        $owner = DB::transaction(function () use ($invitation, $validated): WorkspaceOwner {
            $owner = WorkspaceOwner::updateOrCreate(
                ['phone_number' => $invitation->phone_number],
                [
                    'full_name' => $validated['full_name'],
                    'password' => $validated['password'],
                    'status' => 'active',
                ],
            );
            abort_if($owner->ownedWorkspace()->whereKeyNot($invitation->workspace_id)->exists(), 422, 'This account already owns another workspace.');

            $workspace = $invitation->workspace()->lockForUpdate()->firstOrFail();
            WorkspaceOwnershipChange::create([
                'workspace_id' => $workspace->id,
                'previous_owner_id' => $workspace->owner_id,
                'new_owner_id' => $workspace->owner_id,
                'changed_by_admin_id' => $invitation->invited_by_admin_id,
                'reason' => 'WorkspaceOwner accepted workspace invitation: '.$owner->id,
            ]);
            $workspace->update(['workspace_owner_id' => $owner->id]);
            $invitation->update(['accepted_at' => now()]);

            return $owner;
        });

        Auth::guard('workspace_owner')->login($owner);

        return redirect()->route('workspace.settings.edit')->with('success', 'تم قبول الدعوة وربط مساحة العمل بحسابك.');
    }

    public function login(WorkspaceLoginRequest $request): RedirectResponse
    {
        $phone = $request->string('phone_number')->trim()->value();
        $owner = WorkspaceOwner::query()
            ->where('phone_number', $phone)
            ->orWhere('phone_number_normalized', WorkspaceOwner::normalizePhone($phone))
            ->first();

        if (! $owner || ! Hash::check($request->string('password')->value(), $owner->password)) {
            return back()
                ->withInput($request->only('phone_number'))
                ->withErrors(['phone_number' => 'هذا الحساب غير مسجل كشريك لدينا أو البيانات المدخلة غير صحيحة.']);
        }

        // Enforce the workspace lifecycle before granting a usable session.
        //    (Rejected registrations are hard-deleted, so they never reach here.)
        $workspace = $owner->ownedWorkspace()->first();

        if ($workspace === null || ! $workspace->isApproved()) {
            Auth::guard('workspace_owner')->logout();
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

        Auth::guard('workspace_owner')->login($owner, true);
        $owner->forceFill(['last_login_at' => now()])->save();
        $request->session()->regenerate();

        return redirect()->intended(route('workspace.settings.edit'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('workspace_owner')->logout();

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
