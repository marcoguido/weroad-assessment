<?php

namespace App\Http\Controllers\Api\v1\Travels;

use App\Actions\Travel\UpdateTravel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Travels\Data\TravelData;
use App\Http\Requests\Api\v1\Travels\UpdateTravelRequest;
use App\Http\Responses\Api\V1\Resources\TravelResource;
use App\Models\Identifiers\TravelId;
use App\OpenApi\Parameters\TravelUpdateParameters;
use App\OpenApi\RequestBodies\Travels\UpdateTravelRequestBody;
use App\OpenApi\Responses\Travels\TravelUpdatedResponse;
use App\OpenApi\SecuritySchemes\TokenSecurityScheme;
use Illuminate\Http\JsonResponse;
use Spatie\LaravelData\Exceptions\InvalidDataClass;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class UpdateTravelController extends Controller
{
    /**
     * Updates an existing travel
     *
     * @throws InvalidDataClass
     */
    #[OpenApi\Operation(
        tags: ['Travel'],
        security: TokenSecurityScheme::class,
        method: 'PATCH',
    )]
    #[OpenApi\Parameters(factory: TravelUpdateParameters::class)]
    #[OpenApi\RequestBody(factory: UpdateTravelRequestBody::class)]
    #[OpenApi\Response(factory: TravelUpdatedResponse::class)]
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
