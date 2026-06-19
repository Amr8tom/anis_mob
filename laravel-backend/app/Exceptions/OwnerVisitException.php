<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

/**
 * Thrown by owner-side manual check-in/out actions. The web controller catches
 * it and redirects back with the (user-facing) message.
 */
final class OwnerVisitException extends RuntimeException {}
