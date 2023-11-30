<?php

namespace App\OpenApi\Responses\Travels;

use App\OpenApi\Schemas\Travels\TravelOutputSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

abstract class TravelManipulationResponse extends ResponseFactory
{
    abstract public function getStatusCode(): int;

    abstract public function getDescription(): string;

    public function build(): Response
    {
        return Response::create()
            ->statusCode($this->getStatusCode())
            ->description($this->getDescription())
            ->content(
                MediaType::json()->schema(TravelOutputSchema::ref()),
            );
    }
}
