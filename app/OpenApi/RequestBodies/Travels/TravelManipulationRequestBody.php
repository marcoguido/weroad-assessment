<?php

namespace App\OpenApi\RequestBodies\Travels;

use App\OpenApi\Schemas\Travels\TravelInputSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use Vyuldashev\LaravelOpenApi\Factories\RequestBodyFactory;

abstract class TravelManipulationRequestBody extends RequestBodyFactory
{
    /**
     * Request body description
     */
    abstract public function getDescription(): string;

    public function build(): RequestBody
    {
        return RequestBody::create()
            ->required()
            ->description($this->getDescription())
            ->content(
                MediaType::json()->schema(TravelInputSchema::ref()),
            );
    }
}
