<?php

declare(strict_types=1);

namespace App\Domain\Auth\Actions;

/**
 * DEPRECATED / DISABLED.
 *
 * Guest mode is local-only on the Flutter side. The backend intentionally does
 * NOT create database users or Sanctum tokens for guests, so there is no guest
 * login endpoint. This class is retained only as an empty placeholder because
 * the file cannot be removed in this environment; it must not be wired to a route.
 */
final class GuestLoginAction
{
}
