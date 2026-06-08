<?php

declare(strict_types=1);

namespace App\Domain\WorkspacePortal\Data;

use App\Http\Requests\WorkspacePortal\WorkspaceRegisterRequest;

final readonly class WorkspaceRegistrationData
{
    public function __construct(
        public string $fullName,
        public string $phoneNumber,
        public string $password,
        public string $whatsappNumber,
        public string $workspaceName,
        public string $address,
        public float $latitude,
        public float $longitude,
        public int $dayCalculationHours,
    ) {}

    public static function fromRequest(WorkspaceRegisterRequest $request): self
    {
        return new self(
            fullName: $request->string('full_name')->trim()->value(),
            phoneNumber: $request->string('phone_number')->trim()->value(),
            password: $request->string('password')->value(),
            whatsappNumber: $request->string('whatsapp_number')->trim()->value(),
            workspaceName: $request->string('workspace_name')->trim()->value(),
            address: $request->string('address')->trim()->value(),
            latitude: $request->float('latitude'),
            longitude: $request->float('longitude'),
            dayCalculationHours: $request->integer('day_calculation_hours', 8),
        );
    }
}
