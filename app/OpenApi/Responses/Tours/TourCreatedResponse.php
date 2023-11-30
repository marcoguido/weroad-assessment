<?php

namespace App\OpenApi\Responses\Tours;

use Illuminate\Http\JsonResponse;

class TourCreatedResponse extends TourManipulationResponse
{
    public function getStatusCode(): int
    {
        return JsonResponse::HTTP_CREATED;
    }

    public function getDescription(): string
    {
        return 'Tour successfully created';
    }
}
