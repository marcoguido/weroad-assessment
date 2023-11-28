<?php

namespace App\Http\Controllers\Api\v1\Tours;

use App\Actions\Tour\StoreTour;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Tours\CreateTourRequest;
use App\Http\Requests\Api\v1\Tours\Data\TourData;
use App\Http\Responses\Api\V1\Resources\TourResource;
use App\Models\Tour;
use Illuminate\Http\JsonResponse;
use Spatie\LaravelData\Exceptions\InvalidDataClass;

class CreateTourController extends Controller
{
    /**
     * Creates a new Tour entity bound to the travel
     * identified by URL parameter
     *
     * @throws InvalidDataClass
     */
    public function __invoke(CreateTourRequest $request, StoreTour $storeAction): JsonResponse
    {
        /** @var TourData $tourData */
        $tourData = $request->getData();
        $newTour = $storeAction->execute($tourData);

        return new JsonResponse(
            data: TourResource::fromModel($newTour),
            status: JsonResponse::HTTP_CREATED,
        );
    }
}
