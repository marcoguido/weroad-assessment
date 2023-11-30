<?php

namespace App\OpenApi\RequestBodies\Travels;

class CreateTravelRequestBody extends TravelManipulationRequestBody
{
    public function getDescription(): string
    {
        return 'Travel creation payload';
    }
}
