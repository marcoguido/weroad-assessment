<?php

namespace App\Http\Controllers\Api\v1\Tours;

use App\Actions\Tour\UpdateTour;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Tours\Data\TourData;
use App\Http\Requests\Api\v1\Tours\UpdateTourRequest;
use App\Http\Responses\Api\V1\Resources\TourResource;
use App\Models\Identifiers\TourId;
use Illuminate\Http\JsonResponse;
use Spatie\LaravelData\Exceptions\InvalidDataClass;

class UpdateTourController extends Controller
{
    /**
     * @throws InvalidDataClass
     */
    public function __invoke(
        TourId $tourId,
        UpdateTourRequest $request,
        UpdateTour $updateAction
    ): JsonResponse
    {
        /** @var TourData $tourData */
        $tourData = $request->getData();
        $tour = $updateAction->execute($tourId, $tourData);

        return new JsonResponse(
            data: TourResource::from($tour),
            status: JsonResponse::HTTP_ACCEPTED,
        );
    }
}
