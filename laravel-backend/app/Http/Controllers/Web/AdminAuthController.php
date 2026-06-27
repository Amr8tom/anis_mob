<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\TotpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RuntimeException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

final class AdminAuthController extends Controller
{
    private const MAX_ATTEMPTS = 5;

    public function showLogin(): View
    {
        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'phone_number' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
        $user = User::where('phone_number', $credentials['phone_number'])->first();

        if ($user?->admin_locked_until?->isFuture()) {
            return back()->withErrors(['phone_number' => 'Account temporarily locked. Try again later.']);
        }

        if ($user === null || $user->role !== UserRole::ADMIN || ! $this->passwordMatches($user, $credentials['password'])) {
            if ($user?->role === UserRole::ADMIN) {
                $attempts = $user->admin_failed_login_attempts + 1;
                $user->forceFill([
                    'admin_failed_login_attempts' => $attempts,
                    'admin_locked_until' => $attempts >= self::MAX_ATTEMPTS ? now()->addMinutes(15) : null,
                ])->save();
            }

            return back()->withErrors(['phone_number' => 'Invalid credentials.']);
        }

        $user->forceFill(['admin_failed_login_attempts' => 0, 'admin_locked_until' => null])->save();
        $request->session()->put('admin_mfa_user_id', $user->id);

        if ($user->admin_mfa_enabled) {
            return redirect()->route('admin.mfa');
        }

        return $this->finishLogin($request, $user);
    }

    public function showMfa(Request $request): View|RedirectResponse
    {
        return $request->session()->has('admin_mfa_user_id')
            ? view('admin.auth.mfa')
            : redirect()->route('admin.login');
    }

    public function verifyMfa(Request $request, TotpService $totp): RedirectResponse
    {
        $validated = $request->validate(['code' => ['required', 'digits:6']]);
        $user = User::find($request->session()->get('admin_mfa_user_id'));

        if ($user === null || ! $user->admin_mfa_enabled || ! $totp->verify((string) $user->admin_mfa_secret, $validated['code'])) {
            return back()->withErrors(['code' => 'Invalid authentication code.']);
        }

        return $this->finishLogin($request, $user);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    private function finishLogin(Request $request, User $user): RedirectResponse
    {
        Auth::guard('admin')->login($user);
        $request->session()->forget('admin_mfa_user_id');
        $request->session()->regenerate();

        return redirect()->intended('/admin/dashboard');
    }

    private function passwordMatches(User $user, string $plainPassword): bool
    {
        $storedPassword = (string) $user->password;

        if (Hash::isHashed($storedPassword)) {
            try {
                $matches = Hash::check($plainPassword, $storedPassword);
            } catch (RuntimeException) {
                return false;
            }

            if ($matches && Hash::needsRehash($storedPassword)) {
                $user->forceFill(['password' => Hash::make($plainPassword)])->save();
            }

            return $matches;
        }

        if (! hash_equals($storedPassword, $plainPassword)) {
            return false;
        }

        $user->forceFill(['password' => Hash::make($plainPassword)])->save();

        return true;
    }
}
