<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\WorkspaceCenterGradeLevel;
use App\Models\WorkspaceCenterSubject;
use App\Models\WorkspaceCenterTeacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

final class WorkspaceEducationController extends Controller
{
    public function index(): View
    {
        $workspace = Auth::user()->ownedWorkspace;

        return view('workspace.education.index', [
            'workspace' => $workspace,
            'teachers' => WorkspaceCenterTeacher::query()
                ->where('workspace_id', $workspace->id)
                ->orderByDesc('is_active')
                ->orderBy('name')
                ->paginate(12, ['*'], 'teachers_page'),
            'subjects' => WorkspaceCenterSubject::query()
                ->where('workspace_id', $workspace->id)
                ->orderByDesc('is_active')
                ->orderBy('name')
                ->get(),
            'gradeLevels' => WorkspaceCenterGradeLevel::query()
                ->where('workspace_id', $workspace->id)
                ->orderByDesc('is_active')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function storeTeacher(Request $request): RedirectResponse
    {
        $workspace = Auth::user()->ownedWorkspace;
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        WorkspaceCenterTeacher::create([
            'workspace_id' => $workspace->id,
            'name' => trim($validated['name']),
            'phone_number' => $validated['phone_number'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'is_active' => true,
        ]);

        return back()->with('success', 'تم إضافة المدرس / المحاضر.');
    }

    public function storeSubject(Request $request): RedirectResponse
    {
        $workspace = Auth::user()->ownedWorkspace;
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:120',
                Rule::unique('workspace_center_subjects', 'name')->where('workspace_id', $workspace->id),
            ],
        ]);

        WorkspaceCenterSubject::create([
            'workspace_id' => $workspace->id,
            'name' => trim($validated['name']),
            'is_active' => true,
        ]);

        return back()->with('success', 'تم إضافة المادة.');
    }

    public function storeGradeLevel(Request $request): RedirectResponse
    {
        $workspace = Auth::user()->ownedWorkspace;
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:120',
                Rule::unique('workspace_center_grade_levels', 'name')->where('workspace_id', $workspace->id),
            ],
        ]);

        WorkspaceCenterGradeLevel::create([
            'workspace_id' => $workspace->id,
            'name' => trim($validated['name']),
            'is_active' => true,
        ]);

        return back()->with('success', 'تم إضافة المرحلة الدراسية.');
    }

    public function toggleTeacher(string $teacher): RedirectResponse
    {
        $workspace = Auth::user()->ownedWorkspace;
        $model = WorkspaceCenterTeacher::query()
            ->where('workspace_id', $workspace->id)
            ->where('id', $teacher)
            ->firstOrFail();
        $model->update(['is_active' => ! $model->is_active]);

        return back()->with('success', $model->is_active ? 'تم تفعيل المدرس.' : 'تم إيقاف المدرس.');
    }

    public function toggleSubject(string $subject): RedirectResponse
    {
        $workspace = Auth::user()->ownedWorkspace;
        $model = WorkspaceCenterSubject::query()
            ->where('workspace_id', $workspace->id)
            ->where('id', $subject)
            ->firstOrFail();
        $model->update(['is_active' => ! $model->is_active]);

        return back()->with('success', $model->is_active ? 'تم تفعيل المادة.' : 'تم إيقاف المادة.');
    }

    public function toggleGradeLevel(string $gradeLevel): RedirectResponse
    {
        $workspace = Auth::user()->ownedWorkspace;
        $model = WorkspaceCenterGradeLevel::query()
            ->where('workspace_id', $workspace->id)
            ->where('id', $gradeLevel)
            ->firstOrFail();
        $model->update(['is_active' => ! $model->is_active]);

        return back()->with('success', $model->is_active ? 'تم تفعيل المرحلة.' : 'تم إيقاف المرحلة.');
    }
}
