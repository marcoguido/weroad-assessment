<?php

namespace App\OpenApi\Responses\Tours;

use Illuminate\Http\JsonResponse;

class TourUpdatedResponse extends TourManipulationResponse
{
    public function getStatusCode(): int
    {
        return JsonResponse::HTTP_ACCEPTED;
    }

    public function getDescription(): string
    {
        return 'Tour successfully updated';
    }
}
