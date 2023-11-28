<?php

namespace App\Http\Requests\Api\v1\Tours\Data;

use App\Models\Identifiers\TravelId;
use DateTime;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithCastable;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class TourData extends Data
{
    public function __construct(
        #[WithCastable(TravelId::class)]
        public readonly TravelId $travelId,
        public readonly string $name,
        #[WithCast(DateTimeInterfaceCast::class)]
        public readonly DateTime $startingDate,
        #[WithCast(DateTimeInterfaceCast::class)]
        public readonly DateTime $endingDate,
        public readonly int $price,
    ) {
    }
}
