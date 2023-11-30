<?php

namespace App\Http\Requests\Api\v1\Travels\Data;

use Spatie\LaravelData\Data;

/**
 * @property  array<string, int> $moods
 */
class TravelData extends Data
{
    public function __construct(
        public readonly bool $isPublic,
        public readonly string $name,
        public readonly string $description,
        public readonly int $numberOfDays,
        public readonly array $moods,
    ) {
    }
}
