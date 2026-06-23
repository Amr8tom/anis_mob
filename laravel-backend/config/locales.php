<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Supported locales (single source of truth)
|--------------------------------------------------------------------------
| Adding a future language = add a row here + a lang/{code} folder.
| Nothing else in code needs to change. Use ISO codes (tr, NOT turkey).
*/

return [
    'default' => env('PORTAL_LOCALE', 'ar'),
    'fallback' => env('APP_FALLBACK_LOCALE', 'en'),

    'supported' => [
        'ar' => ['name' => 'Arabic',  'native' => 'العربية', 'dir' => 'rtl'],
        'en' => ['name' => 'English', 'native' => 'English', 'dir' => 'ltr'],
        'tr' => ['name' => 'Turkish', 'native' => 'Türkçe',  'dir' => 'ltr'],
    ],
];
