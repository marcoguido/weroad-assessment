<?php

namespace App\Http\Responses\Api\V1\Resources;

use DateTime;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

/**
 * @property array<string, int> $moods
 */
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
        #[WithCast(DateTimeInterfaceCast::class)]
        public readonly DateTime $createdAt,
        #[WithCast(DateTimeInterfaceCast::class)]
        public readonly DateTime $updatedAt,
    ) {
    }
}
