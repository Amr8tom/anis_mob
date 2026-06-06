<?php

declare(strict_types=1);

namespace App\Domain\Auth\Data;

use App\Http\Requests\Auth\LoginRequest;

final readonly class LoginData
{
    public function __construct(
        public string $identifier,   // phone number OR email
        public string $password,
    ) {}

    public static function fromRequest(LoginRequest $request): self
    {
        // Accept `login` (preferred), or legacy `phone_number` / `email` keys.
        $identifier = $request->string('login')->trim()->value()
            ?: $request->string('phone_number')->trim()->value()
            ?: $request->string('email')->trim()->value();

        return new self(
            identifier: $identifier,
            password: $request->string('password')->value(),
        );
    }
}
