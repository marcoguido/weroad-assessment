<?php

namespace App\OpenApi\Schemas\Pagination;

use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException;
use GoldSpecDigital\ObjectOrientedOAS\Objects\AllOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\AnyOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Not;
use GoldSpecDigital\ObjectOrientedOAS\Objects\OneOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\SchemaFactory;

class PaginationLinksSchema extends SchemaFactory implements Reusable
{
    /**
     * @return AllOf|OneOf|AnyOf|Not|Schema
     * @throws InvalidArgumentException
     */
    public function build(): SchemaContract
    {
        return Schema::array('PaginationLinks')
            ->items(
                Schema::object()
                    ->properties(
                        Schema::string('url')
                            ->required()
                            ->nullable(),
                        Schema::string('label')
                            ->required()
                            ->nullable(false),
                        Schema::boolean('active')
                            ->required()
                            ->nullable(false),
                    ),
            );
    }
}
