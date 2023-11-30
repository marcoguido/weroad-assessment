<?php

namespace App\OpenApi\RequestBodies\Tours;

use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use Vyuldashev\LaravelOpenApi\Factories\RequestBodyFactory;

class CreateTourRequestBody extends TourManipulationRequestBody
{
    public function getDescription(): string
    {
        return "Tour creation payload";
    }
}
