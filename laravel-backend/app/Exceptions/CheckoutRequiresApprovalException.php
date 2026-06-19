<?php

declare(strict_types=1);

namespace App\Exceptions;

/**
 * A paid visitor tried to self-checkout at a workspace that requires owner
 * approval. The client should send a checkout request instead.
 */
final class CheckoutRequiresApprovalException extends ApiException
{
    protected int $status = 403;

    public function __construct(string $message = 'This workspace requires owner approval to check out. Please send a checkout request.')
    {
        parent::__construct($message);
    }
}
