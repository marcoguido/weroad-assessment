<?php

namespace App\OpenApi\Schemas\Tours;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;

class TourInputSchema extends TourSchema implements Reusable
{
    public function getObjectId(): string
    {
        return 'TourInput';
    }

    public function getObjectProperties(): array
    {
        return [
            Schema::string('travelId')
                ->maxLength(36)
                ->nullable(false)
                ->required(),
            Schema::string('name')
                ->maxLength(255)
                ->nullable(false)
                ->required(),
            Schema::string('startingDate')
                ->format(Schema::FORMAT_DATE)
                ->nullable(false)
                ->required()
                ->example('2023-01-01'),
            Schema::string('endingDate')
                ->format(Schema::FORMAT_DATE)
                ->nullable(false)
                ->required()
                ->example('2023-01-10'),
            Schema::integer('price')
                ->minimum(0)
                ->nullable(false)
                ->required(),
        ];
    }
}
