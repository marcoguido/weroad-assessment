<?php

namespace App\Http\Controllers\Api\v1\Tours;

use App\Actions\Tour\GetPaginatedTravelTours;
use App\Http\Controllers\Controller;
use App\Http\Responses\Api\V1\Resources\TourResource;
use App\Models\Identifiers\TravelId;
use App\Models\Travel;
use Illuminate\Http\JsonResponse;

class ListToursController extends Controller
{
    public function __construct(
        private GetPaginatedTravelTours $action,
    ) {
    }

    public function byTravelId(TravelId $travelId): JsonResponse
    {
        return $this->getTours($travelId);
    }

    public function byTravelSlug(Travel $travel, GetPaginatedTravelTours $fetchAction): JsonResponse
    {
        return $this->getTours($travel->identifier);
    }

    private function getTours(TravelId $travelId): JsonResponse
    {
        $tours = $this->action->execute($travelId);

        return new JsonResponse(
            data: TourResource::collection($tours),
        );
    }
}
