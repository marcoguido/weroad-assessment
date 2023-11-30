<?php

namespace App\Http\Controllers\Api\v1\Tours;

use App\Actions\Tour\StoreTour;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Tours\CreateTourRequest;
use App\Http\Requests\Api\v1\Tours\Data\TourData;
use App\Http\Responses\Api\V1\Resources\TourResource;
use App\OpenApi\RequestBodies\Tours\CreateTourRequestBody;
use App\OpenApi\Responses\Tours\TourCreatedResponse;
use App\OpenApi\SecuritySchemes\TokenSecurityScheme;
use Illuminate\Http\JsonResponse;
use Spatie\LaravelData\Exceptions\InvalidDataClass;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class CreateTourController extends Controller
{
    /**
     * Creates a new tour
     *
     * @throws InvalidDataClass
     */
    #[OpenApi\Operation(
        tags: ['Tour'],
        security: TokenSecurityScheme::class,
        method: 'POST',
    )]
    #[OpenApi\RequestBody(factory: CreateTourRequestBody::class)]
    #[OpenApi\Response(factory: TourCreatedResponse::class)]
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
