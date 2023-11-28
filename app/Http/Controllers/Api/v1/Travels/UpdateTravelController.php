<?php

namespace App\Http\Controllers\Api\v1\Travels;

use App\Actions\Travel\UpdateTravel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Travels\Data\TravelData;
use App\Http\Requests\Api\v1\Travels\UpdateTravelRequest;
use App\Http\Responses\Api\V1\Resources\TravelResource;
use App\Models\Identifiers\TravelId;
use Illuminate\Http\JsonResponse;
use Spatie\LaravelData\Exceptions\InvalidDataClass;

class UpdateTravelController extends Controller
{
    /**
     * @throws InvalidDataClass
     */
    public function __invoke(
        TravelId $travelId,
        UpdateTravelRequest $request,
        UpdateTravel $updateAction
    ): JsonResponse
    {
        /** @var TravelData $travelData */
        $travelData = $request->getData();
        $travel = $updateAction->execute($travelId, $travelData);

        return new JsonResponse(
            data: TravelResource::from($travel),
            status: JsonResponse::HTTP_ACCEPTED,
        );
    }
}
