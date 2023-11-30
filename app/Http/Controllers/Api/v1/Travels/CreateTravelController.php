<?php

namespace App\Http\Controllers\Api\v1\Travels;

use App\Actions\Travel\StoreTravel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Travels\CreateTravelRequest;
use App\Http\Requests\Api\v1\Travels\Data\TravelData;
use App\Http\Responses\Api\V1\Resources\TravelResource;
use App\OpenApi\RequestBodies\Travels\CreateTravelRequestBody;
use App\OpenApi\Responses\Travels\TravelCreatedResponse;
use App\OpenApi\SecuritySchemes\TokenSecurityScheme;
use Illuminate\Http\JsonResponse;
use Spatie\LaravelData\Exceptions\InvalidDataClass;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class CreateTravelController extends Controller
{
    /**
     * API to create a new travel
     *
     * @throws InvalidDataClass
     */
    #[OpenApi\Operation(
        tags: ['Travel'],
        security: TokenSecurityScheme::class,
        method: 'POST',
    )]
    #[OpenApi\RequestBody(factory: CreateTravelRequestBody::class)]
    #[OpenApi\Response(factory: TravelCreatedResponse::class)]
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
