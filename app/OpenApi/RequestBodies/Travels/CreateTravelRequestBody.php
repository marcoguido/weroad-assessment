<?php

namespace App\OpenApi\RequestBodies\Travels;

use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use Vyuldashev\LaravelOpenApi\Factories\RequestBodyFactory;

class CreateTravelRequestBody extends TravelManipulationRequestBody
{
    public function getDescription(): string
    {
        return "Travel creation payload";
    }
}
