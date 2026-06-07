<?php

declare(strict_types=1);

namespace App\Domain\Profile\Data;

use App\Enums\Availability;
use App\Enums\Gender;
use App\Http\Requests\Profile\UpdateProfileRequest;

final readonly class UpdateProfileData
{
    /**
     * @param  array<int, string>|null  $interests
     */
    public function __construct(
        public bool $hasFullName,
        public ?string $fullName,
        public bool $hasEmail,
        public ?string $email,
        public bool $hasUniversity,
        public ?string $university,
        public bool $hasStudyField,
        public ?string $studyField,
        public bool $hasGender,
        public ?Gender $gender,
        public bool $hasAvatarUrl,
        public ?string $avatarUrl,
        public bool $hasAvatarColorKey,
        public ?string $avatarColorKey,
        public bool $hasAvailability,
        public ?Availability $availability,
        public bool $hasInterests,
        public ?array $interests,
    ) {}

    public static function fromRequest(UpdateProfileRequest $request): self
    {
        return new self(
            hasFullName: $request->has('full_name'),
            fullName: $request->filled('full_name') ? $request->string('full_name')->trim()->value() : null,
            hasEmail: $request->has('email'),
            email: $request->filled('email') ? $request->string('email')->trim()->lower()->value() : null,
            hasUniversity: $request->has('university'),
            university: $request->filled('university') ? $request->string('university')->trim()->value() : null,
            hasStudyField: $request->has('study_field'),
            studyField: $request->filled('study_field') ? $request->string('study_field')->trim()->value() : null,
            hasGender: $request->has('gender'),
            gender: $request->filled('gender') ? Gender::from($request->string('gender')->upper()->value()) : null,
            hasAvatarUrl: $request->has('avatar_url'),
            avatarUrl: $request->filled('avatar_url') ? $request->string('avatar_url')->trim()->value() : null,
            hasAvatarColorKey: $request->has('avatar_color_key'),
            avatarColorKey: $request->filled('avatar_color_key') ? $request->string('avatar_color_key')->trim()->value() : null,
            hasAvailability: $request->has('availability'),
            availability: $request->filled('availability') ? Availability::from($request->string('availability')->upper()->value()) : null,
            hasInterests: $request->has('interests'),
            interests: $request->has('interests') ? $request->array('interests') : null,
        );
    }

    /**
     * Whitelisted persistence values. Request parsing never reaches a repository.
     *
     * @return array<string, mixed>
     */
    public function toAttributes(): array
    {
        $attributes = [];

        if ($this->hasFullName) {
            $attributes['full_name'] = $this->fullName;
        }
        if ($this->hasEmail) {
            $attributes['email'] = $this->email;
        }
        if ($this->hasUniversity) {
            $attributes['university'] = $this->university;
        }
        if ($this->hasStudyField) {
            $attributes['study_field'] = $this->studyField;
        }
        if ($this->hasGender) {
            $attributes['gender'] = $this->gender;
        }
        if ($this->hasAvatarUrl) {
            $attributes['avatar_url'] = $this->avatarUrl;
        }
        if ($this->hasAvatarColorKey) {
            $attributes['avatar_color_key'] = $this->avatarColorKey;
        }
        if ($this->hasAvailability) {
            $attributes['availability'] = $this->availability;
        }
        if ($this->hasInterests) {
            $attributes['interests'] = $this->interests;
        }

        return $attributes;
    }
}
