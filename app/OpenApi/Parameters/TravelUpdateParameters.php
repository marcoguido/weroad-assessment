<?php

namespace App\OpenApi\Parameters;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Illuminate\Support\Str;
use Vyuldashev\LaravelOpenApi\Factories\ParametersFactory;

class TravelUpdateParameters extends ParametersFactory
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
                ->schema(
                    Schema::string()->example(Str::uuid()->toString()),
                ),
        ];
    }
}
