<?php

namespace App\Http\Controllers\Api\v1\Travels;

use App\Actions\Travel\GetPaginatedTravels;
use App\Http\Controllers\Controller;
use App\Http\Responses\Api\V1\Resources\TravelResource;
use App\OpenApi\Responses\Travels\ListTravelsResponse;
use App\OpenApi\SecuritySchemes\TokenSecurityScheme;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class ListTravelsController extends Controller
{
    public function __construct(
        private readonly GetPaginatedTravels $fetchAction,
    ) {
    }

    /**
     * Gets all available travels
     *
     * Only when the API is invoked by an authenticated user private
     * travels can be retrieved
     */
    #[OpenApi\Operation(
        tags: ['Travel'],
        security: TokenSecurityScheme::class,
        method: 'GET',
    )]
    #[OpenApi\Response(
        factory: ListTravelsResponse::class,
        statusCode: JsonResponse::HTTP_OK,
    )]
    public function asAdministrator(): JsonResponse
    {
        return $this->fetchTravels(onlyPublic: ! Auth::check());
    }

    /**
     * Gets all publicly available travels
     */
    #[OpenApi\Operation(
        tags: ['Travel'],
        method: 'GET',
    )]
    #[OpenApi\Response(
        factory: ListTravelsResponse::class,
        statusCode: JsonResponse::HTTP_OK,
    )]
    public function publiclyAvailable(): JsonResponse
    {
        return $this->fetchTravels(onlyPublic: true);
    }

    private function fetchTravels(bool $onlyPublic): JsonResponse
    {
        $travels = $this->fetchAction->execute($onlyPublic);

        return new JsonResponse(
            data: TravelResource::collection($travels),
        );
    }
}
