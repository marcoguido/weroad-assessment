<?php

namespace App\Http\Controllers\Api\v1\Tours;

use App\Actions\Tour\GetPaginatedTravelTours;
use App\Http\Controllers\Controller;
use App\Http\Responses\Api\V1\Resources\TourResource;
use App\Models\Identifiers\TravelId;
use App\Models\Travel;
use App\OpenApi\Parameters\ListToursByTravelIdParameters;
use App\OpenApi\Parameters\ListToursByTravelSlugParameters;
use App\OpenApi\Responses\Tours\ListToursResponse;
use App\OpenApi\SecuritySchemes\TokenSecurityScheme;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class ListToursController extends Controller
{
    public function __construct(
        private GetPaginatedTravelTours $action,
    ) {
    }

    /**
     * Admin-only API to fetch all tours by their parent travel
     */
    #[OpenApi\Operation(
        tags: ['Tour'],
        security: TokenSecurityScheme::class,
        method: 'GET',
    )]
    #[OpenApi\Parameters(factory: ListToursByTravelIdParameters::class)]
    #[OpenApi\Response(
        factory: ListToursResponse::class,
        statusCode: JsonResponse::HTTP_OK,
    )]
    public function byTravelId(TravelId $travelId): JsonResponse
    {
        return $this->getTours($travelId);
    }

    /**
     * Public API to fetch all public tours associated to given travel
     */
    #[OpenApi\Operation(
        tags: ['Tour'],
        method: 'GET',
    )]
    #[OpenApi\Parameters(factory: ListToursByTravelSlugParameters::class)]
    #[OpenApi\Response(factory: ListToursResponse::class)]
    public function byTravelSlug(Travel $travel): JsonResponse
    {
        return $this->getTours($travel->identifier);
    }

    private function getTours(TravelId $travelId): JsonResponse
    {
        // Forcing empty results when no user is logged and tries
        // to fetch tours belonging to private travel
        $tours = $this->action->execute(
            travelId: $travelId,
            publicTravelToursOnly: ! Auth::check(),
        );

        return new JsonResponse(
            data: TourResource::collection($tours),
        );
    }
}
