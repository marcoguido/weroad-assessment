<?php

namespace App\Http\Responses\Api\V1\Resources;

use App\Models\Tour;
use DateTime;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class TourResource extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly string $travelId,
        public readonly string $name,
        public readonly string $startingDate,
        public readonly string $endingDate,
        public readonly int $price,
        #[WithCast(DateTimeInterfaceCast::class)]
        public readonly DateTime $createdAt,
        #[WithCast(DateTimeInterfaceCast::class)]
        public readonly DateTime $updatedAt,
    ) {
    }

    public static function fromModel(Tour $tour): static
    {
        return new static(
            $tour->id,
            $tour->travelId,
            $tour->name,
            $tour->startingDate->format(Tour::DATE_FORMAT),
            $tour->endingDate->format(Tour::DATE_FORMAT),
            $tour->price,
        );
    }
}
