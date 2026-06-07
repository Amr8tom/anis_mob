<?php

declare(strict_types=1);

namespace App\Domain\Profile\Support;

use App\Models\User;

final readonly class ProfileCompletionSummary
{
    private const FIELDS = [
        'email' => 'email',
        'university' => 'university',
        'studyField' => 'study_field',
        'gender' => 'gender',
        'interests' => 'interests',
    ];

    /**
     * @param  array<int, string>  $missingFields
     */
    public function __construct(
        public bool $completed,
        public int $percentage,
        public array $missingFields,
    ) {}

    public static function forUser(User $user): self
    {
        $missing = [];

        foreach (self::FIELDS as $apiField => $modelField) {
            $value = $user->getAttribute($modelField);
            if ($value === null
                || (is_string($value) && trim($value) === '')
                || (is_array($value) && $value === [])) {
                $missing[] = $apiField;
            }
        }

        $completedCount = count(self::FIELDS) - count($missing);
        $percentage = (int) round(($completedCount / count(self::FIELDS)) * 100);

        return new self(
            completed: $missing === [],
            percentage: $percentage,
            missingFields: $missing,
        );
    }
}
