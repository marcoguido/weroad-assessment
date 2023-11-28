<?php

namespace App\Http\Responses\Api\V1\Resources;

use App\Models\Tour;
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
