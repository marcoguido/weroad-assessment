<?php

namespace App\Http\Controllers\Api\v1\Travels;

use App\Actions\Travel\StoreTravel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Travels\CreateTravelRequest;
use App\Http\Requests\Api\v1\Travels\Data\TravelData;
use App\Http\Responses\Api\V1\Resources\TravelResource;
use Illuminate\Http\JsonResponse;
use Spatie\LaravelData\Exceptions\InvalidDataClass;

class CreateTravelController extends Controller
{
    /**
     * @throws InvalidDataClass
     */
    public function __invoke(CreateTravelRequest $request, StoreTravel $storeAction): JsonResponse
    {
        /** @var TravelData $travelData */
        $travelData = $request->getData();
        $newTravel = $storeAction->execute($travelData);

        return new JsonResponse(
            data: TravelResource::from($newTravel),
            status: JsonResponse::HTTP_CREATED,
        );
    }
}
