<?php

namespace App\OpenApi\Responses\Tours;

use App\OpenApi\Schemas\Tours\TourOutputSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

abstract class TourManipulationResponse extends ResponseFactory
{
    public abstract function getStatusCode(): int;
    public abstract function getDescription(): string;

    public function build(): Response
    {
        return Response::create()
            ->statusCode($this->getStatusCode())
            ->description($this->getDescription())
            ->content(
                MediaType::json()->schema(TourOutputSchema::ref()),
            );
    }
}
