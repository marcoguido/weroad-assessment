<?php

namespace App\OpenApi\RequestBodies\Tours;

use App\OpenApi\Schemas\Tours\TourInputSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use Vyuldashev\LaravelOpenApi\Factories\RequestBodyFactory;

abstract class TourManipulationRequestBody extends RequestBodyFactory
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
                MediaType::json()->schema(TourInputSchema::ref()),
            );
    }
}
