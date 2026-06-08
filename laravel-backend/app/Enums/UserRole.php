<?php

declare(strict_types=1);

namespace App\Enums;

enum UserRole: string
{
    case USER = 'USER';
    case ADMIN = 'ADMIN';
    case WORKSPACE_OWNER = 'WORKSPACE_OWNER';
}
