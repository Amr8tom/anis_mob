<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

final class LocaleController extends Controller
{
    public function switch(Request $request): RedirectResponse
    {
        $codes = array_keys((array) config('locales.supported', []));
        $validated = $request->validate([
            'locale' => ['required', 'string', 'in:'.implode(',', $codes)],
        ]);
        $locale = $validated['locale'];

        // Sticky for the portal session + a year-long cookie for pre-login pages.
        $request->session()->put('locale', $locale);
        Cookie::queue('locale', $locale, 60 * 24 * 365);

        // Persist on the authenticated owner/user so it follows them across devices.
        $account = $request->user('workspace_owner') ?? $request->user();
        if ($account !== null) {
            $account->forceFill(['locale' => $locale])->save();
        }

        return back();
    }
}
