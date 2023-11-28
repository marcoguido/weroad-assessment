<?php

namespace App\Http\Controllers\Api\v1\Travels;

use App\Actions\Travel\StoreTravel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Travels\CreateTravelRequest;
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
        $newTravel = $storeAction->execute(
            $request->getData()
        );

        return new JsonResponse(
            data: TravelResource::from($newTravel->refresh()),
            status: JsonResponse::HTTP_CREATED,
        );
    }
}
