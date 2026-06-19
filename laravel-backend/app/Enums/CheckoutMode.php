<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Controls how a workspace lets visitors check out.
 *
 * DIRECT   – visitors check themselves out instantly (default, legacy behaviour).
 * APPROVAL – paid visitors must request checkout; the owner approves it from the
 *            dashboard, which performs the real check-out. Free visits always
 *            check out directly regardless of this mode.
 */
enum CheckoutMode: string
{
    case DIRECT = 'DIRECT';
    case APPROVAL = 'APPROVAL';
}
