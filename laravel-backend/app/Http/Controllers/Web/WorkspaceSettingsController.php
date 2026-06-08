<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\WorkspacePortal\Actions\UpdateWorkspaceAction;
use App\Domain\WorkspacePortal\Data\WorkspaceUpdateData;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkspacePortal\WorkspaceUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class WorkspaceSettingsController extends Controller
{
    public function edit(): View
    {
        $workspace = Auth::user()->ownedWorkspace;

        return view('workspace.settings', compact('workspace'));
    }

    public function update(
        WorkspaceUpdateRequest $request,
        UpdateWorkspaceAction $action
    ): RedirectResponse {
        $workspace = Auth::user()->ownedWorkspace;

        $action->handle($workspace, WorkspaceUpdateData::fromRequest($request));

        return redirect()
            ->route('workspace.settings.edit')
            ->with('success', 'تم تحديث بيانات مساحة العمل بنجاح.');
    }
}
