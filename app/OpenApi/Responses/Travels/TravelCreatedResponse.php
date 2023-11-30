<?php

namespace App\OpenApi\Responses\Travels;

use App\OpenApi\Responses\Travels\TravelManipulationResponse;
use Illuminate\Http\JsonResponse;

class TravelCreatedResponse extends TravelManipulationResponse
{
    public function getStatusCode(): int
    {
        return JsonResponse::HTTP_CREATED;
    }

    public function getDescription(): string
    {
        return "Travel successfully created";
    }
}
