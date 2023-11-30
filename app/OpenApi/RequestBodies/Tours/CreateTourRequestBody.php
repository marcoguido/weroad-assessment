<?php

namespace App\OpenApi\RequestBodies\Tours;

class CreateTourRequestBody extends TourManipulationRequestBody
{
    public function getDescription(): string
    {
        return 'Tour creation payload';
    }
}
