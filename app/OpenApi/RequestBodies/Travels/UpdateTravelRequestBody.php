<?php

namespace App\OpenApi\RequestBodies\Travels;

class UpdateTravelRequestBody extends TravelManipulationRequestBody
{
    public function getDescription(): string
    {
        return 'Travel update payload';
    }
}
