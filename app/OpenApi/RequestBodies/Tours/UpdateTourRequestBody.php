<?php

namespace App\OpenApi\RequestBodies\Tours;

use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use Vyuldashev\LaravelOpenApi\Factories\RequestBodyFactory;

class UpdateTourRequestBody extends TourManipulationRequestBody
{
    public function getDescription(): string
    {
        return "Tour update payload";
    }
}
