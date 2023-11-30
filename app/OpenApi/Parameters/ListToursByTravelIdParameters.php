<?php

namespace App\OpenApi\Parameters;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class ListToursByTravelIdParameters extends AbstractToursListingParameters
{
    /**
     * @return Parameter[]
     */
    public function build(): array
    {
        return [
            Parameter::path()
                ->name('travelId')
                ->description('Travel identifier')
                ->required()
                ->schema(Schema::string()),
            ...parent::build(),
        ];
    }
}
