<?php

declare(strict_types=1);

namespace App\Domain\Session\Data;

use App\Http\Requests\Buddy\CreateBuddySessionRequest;
use Carbon\CarbonImmutable;

final readonly class CreateSessionData
{
    /**
     * @param  array<int, string>  $rules
     */
    public function __construct(
        public string $hostId,
        public string $workspaceId,
        public string $topic,
        public string $subject,
        public ?string $description,
        public array $rules,
        public CarbonImmutable $startTime,
        public int $maxCapacity,
        public ?string $gift,
    ) {}

    public static function fromRequest(CreateBuddySessionRequest $request, string $hostId): self
    {
        /** @var array<int, string> $rules */
        $rules = $request->input('rules', []);

        return new self(
            hostId: $hostId,
            workspaceId: $request->string('workspaceId')->value(),
            topic: $request->string('topic')->trim()->value(),
            subject: $request->string('subject')->trim()->value(),
            description: $request->filled('description') ? $request->string('description')->trim()->value() : null,
            rules: array_values(array_map(static fn ($r): string => (string) $r, $rules)),
            startTime: CarbonImmutable::parse($request->string('startTime')->value()),
            maxCapacity: (int) $request->integer('maxCapacity'),
            gift: $request->filled('gift') ? $request->string('gift')->trim()->value() : null,
        );
    }
}
