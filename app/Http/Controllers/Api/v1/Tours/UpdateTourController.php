<?php

namespace App\Http\Controllers\Api\v1\Tours;

use App\Actions\Tour\UpdateTour;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Tours\Data\TourData;
use App\Http\Requests\Api\v1\Tours\UpdateTourRequest;
use App\Http\Responses\Api\V1\Resources\TourResource;
use App\Models\Identifiers\TourId;
use App\OpenApi\RequestBodies\Tours\UpdateTourRequestBody;
use App\OpenApi\Responses\Tours\TourUpdatedResponse;
use App\OpenApi\SecuritySchemes\TokenSecurityScheme;
use Illuminate\Http\JsonResponse;
use Spatie\LaravelData\Exceptions\InvalidDataClass;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class UpdateTourController extends Controller
{
    /**
     * API to update an existing tour
     *
     * @throws InvalidDataClass
     */
    #[OpenApi\Operation(
        tags: ['Tour'],
        security: TokenSecurityScheme::class,
        method: 'PATCH',
    )]
    #[OpenApi\RequestBody(factory: UpdateTourRequestBody::class)]
    #[OpenApi\Response(factory: TourUpdatedResponse::class)]
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
