<?php

namespace App\Http\Controllers\Api\v1\Travels;

use App\Actions\Travel\GetPaginatedTravels;
use App\Http\Controllers\Controller;
use App\Http\Responses\Api\V1\Resources\TravelResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ListTravelsController extends Controller
{
    public function __invoke(GetPaginatedTravels $fetchAction): JsonResponse
    {
        $travels = $fetchAction->execute(onlyPublic: ! Auth::check());

        return new JsonResponse(
            data: TravelResource::collection($travels),
        );
    }
}
