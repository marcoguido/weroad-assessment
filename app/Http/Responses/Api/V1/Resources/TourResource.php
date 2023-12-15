<?php

namespace App\Http\Responses\Api\V1\Resources;

use App\Models\Tour;
use DateTime;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

class TourResource extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly string $travelId,
        public readonly string $name,
        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: Tour::DATE_FORMAT)]
        public readonly DateTime $startingDate,
        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: Tour::DATE_FORMAT)]
        public readonly DateTime $endingDate,
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
            $tour->startingDate,
            $tour->endingDate,
            $tour->price,
            $tour->createdAt,
            $tour->updatedAt,
        );
    }
}
