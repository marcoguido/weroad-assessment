<?php

namespace App\OpenApi\RequestBodies\Travels;

use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use Vyuldashev\LaravelOpenApi\Factories\RequestBodyFactory;

class UpdateTravelRequestBody extends TravelManipulationRequestBody
{
    public function getDescription(): string
    {
        return "Travel update payload";
    }
}
