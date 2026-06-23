<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

final class ThemeController extends Controller
{
    public function switch(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'theme' => ['required', 'string', 'in:dark,light'],
        ]);

        $theme = $validated['theme'];

        // Persist as a year-long cookie.
        Cookie::queue('theme', $theme, 60 * 24 * 365);

        // Persist on the authenticated owner so it follows them across devices.
        $owner = $request->user('workspace_owner');
        if ($owner !== null) {
            $owner->forceFill(['theme' => $theme])->save();
        }

        return back();
    }
}
