<?php

namespace App\OpenApi\Schemas\Travels;

use App\OpenApi\Schemas\Travels\TravelSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;

class TravelInputSchema extends TravelSchema implements Reusable
{
    public function getObjectId(): string
    {
        return 'TravelInput';
    }

    public function getObjectProperties(): array
    {
        return [
            Schema::boolean('isPublic')
                ->description('Whether the Travel should be globally available or not')
                ->default(false)
                ->nullable(false)
                ->required(),
            Schema::string('slug')
                ->maxLength(255)
                ->nullable(false)
                ->readOnly(),
            Schema::string('name')
                ->description('Entity identifier')
                ->maxLength(255)
                ->nullable(false)
                ->required(),
            Schema::string('description')
                ->nullable(false),
            Schema::integer('numberOfDays')
                ->minimum(1)
                ->nullable(false)
                ->required(),
            Schema::integer('numberOfNights')
                ->minimum(0)
                ->nullable(false)
                ->readOnly(),
            Schema::array('moods') // FIXME, non accurate structure
                ->description('A key-value object whose key is a mood and the value is a number representing the "mood amount" for the travel')
                ->nullable(false)
                ->required(),
        ];
    }
}
