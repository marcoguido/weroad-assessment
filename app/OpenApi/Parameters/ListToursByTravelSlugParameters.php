<?php

namespace App\OpenApi\Parameters;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Illuminate\Support\Str;

class ListToursByTravelSlugParameters extends AbstractToursListingParameters
{
    /**
     * @return Parameter[]
     */
    public function build(): array
    {
        return [
            Parameter::path()
                ->name('travel')
                ->description('The slug assigned to the travel')
                ->required()
                ->schema(
                    Schema::string()->example(Str::slug('This may be travel slug')),
                ),
            ...parent::build(),
        ];
    }
}
