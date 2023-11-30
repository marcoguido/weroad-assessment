<?php

namespace App\OpenApi\Schemas\Tours;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;

class TourOutputSchema extends TourInputSchema implements Reusable
{
    public function getObjectId(): string
    {
        return 'TourOutput';
    }

    public function getObjectProperties(): array
    {
        return [
            Schema::string('id')
                ->maxLength(36)
                ->nullable(false)
                ->required(),
            ...parent::getObjectProperties(),
            Schema::string('createdAt')
                ->format(Schema::FORMAT_DATE_TIME)
                ->default(null),
            Schema::string('updatedAt')
                ->format(Schema::FORMAT_DATE_TIME)
                ->default(null),
        ];
    }

}
