<?php

namespace App\OpenApi\RequestBodies\Tours;

class UpdateTourRequestBody extends TourManipulationRequestBody
{
    public function getDescription(): string
    {
        return 'Tour update payload';
    }
}
