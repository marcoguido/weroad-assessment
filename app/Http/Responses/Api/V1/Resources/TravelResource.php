<?php

namespace App\Http\Responses\Api\V1\Resources;

use Spatie\LaravelData\Data;

class TravelResource extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly bool $isPublic,
        public readonly string $slug,
        public readonly string $name,
        public readonly string $description,
        public readonly int $numberOfDays,
        public readonly int $numberOfNights,
        public readonly array $moods,
    ) {
    }
}
