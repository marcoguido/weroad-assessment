<?php

namespace App\OpenApi\Responses\Travels;

use Illuminate\Http\JsonResponse;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;

class TravelUpdatedResponse extends TravelManipulationResponse
{
    public function getStatusCode(): int
    {
        return JsonResponse::HTTP_ACCEPTED;
    }

    public function getDescription(): string
    {
        return "Travel successfully updated";
    }
}
