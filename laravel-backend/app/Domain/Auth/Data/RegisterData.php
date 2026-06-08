<?php

declare(strict_types=1);

namespace App\Domain\Auth\Data;

use App\Enums\Gender;
use App\Http\Requests\Auth\RegisterRequest;

final readonly class RegisterData
{
    public function __construct(
        public string $fullName,
        public string $phoneNumber,
        public ?string $email,
        public string $whatsappNumber,
        public string $password,
        public ?Gender $gender,
        public ?string $studyField,
    ) {}

    public static function fromRequest(RegisterRequest $request): self
    {
        $email = $request->filled('email')
            ? $request->string('email')->trim()->lower()->value()
            : null;

        $gender = $request->filled('gender')
            ? Gender::from($request->string('gender')->upper()->value())
            : null;

        return new self(
            fullName: $request->string('full_name')->trim()->value(),
            phoneNumber: $request->string('phone_number')->trim()->value(),
            email: $email,
            whatsappNumber: $request->string('whatsapp_number')->trim()->value(),
            password: $request->string('    ')->value(),
            gender: $gender,
            studyField: $request->filled('study_field')
                ? $request->string('study_field')->trim()->value()
                : null,
        );
    }
}
