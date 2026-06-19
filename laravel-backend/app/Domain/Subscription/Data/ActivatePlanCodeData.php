<?php

declare(strict_types=1);

namespace App\Domain\Subscription\Data;

use App\Http\Requests\Subscription\ActivatePlanCodeRequest;

final readonly class ActivatePlanCodeData
{
    public function __construct(
        public string $code,
    ) {}

    public static function fromRequest(ActivatePlanCodeRequest $request): self
    {
        return new self(
            code: $request->string('code')->trim()->upper()->value(),
        );
    }
}
