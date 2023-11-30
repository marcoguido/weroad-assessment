<?php

namespace App\OpenApi\Parameters;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Example;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\ParametersFactory;

abstract class AbstractToursListingParameters extends ParametersFactory
{
    /**
     * @return Parameter[]
     */
    public function build(): array
    {
        return [
            // Filtering
            Parameter::query()
                ->name('filter[priceFrom]')
                ->description('Filter all tours whose price is **equal or higher** than filter value')
                ->schema(Schema::integer()),
            Parameter::query()
                ->name('filter[priceTo]')
                ->description('Filter all tours whose price is **equal or lower** than filter value')
                ->schema(Schema::integer()),
            Parameter::query()
                ->name('filter[dateFrom]')
                ->description('Filter all tours whose starting date comes after filter value')
                ->schema(
                    Schema::string()->format(Schema::FORMAT_DATE)
                ),
            Parameter::query()
                ->name('filter[dateTo]')
                ->description('Filter all tours whose ending date is **up to** filter value')
                ->schema(
                    Schema::string()->format(Schema::FORMAT_DATE)
                ),
            // Sorting
            Parameter::query()
                ->name('sort')
                ->description('Sort (ASC/DESC) by tour price. If omitted, sorts by tour starting date (ASC)')
                ->required(false)
                ->schema(Schema::string()->nullable())
                ->examples(
                    Example::create('Price (ASC)')->value('price'),
                    Example::create('Price (DESC)')->value('-price'),
                ),
        ];
    }
}
