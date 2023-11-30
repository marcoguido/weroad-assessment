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

class PaginationMetaSchema extends SchemaFactory implements Reusable
{
    /**
     * @return AllOf|OneOf|AnyOf|Not|Schema
     * @throws InvalidArgumentException
     */
    public function build(): SchemaContract
    {
        return Schema::object('PaginationMeta')
            ->properties(
                Schema::integer('current_page')
                    ->required()
                    ->nullable(false),
                Schema::string('first_page_url')
                    ->required()
                    ->nullable(false),
                Schema::integer('from')
                    ->required()
                    ->nullable(false),
                Schema::integer('last_page')
                    ->required()
                    ->nullable(false),
                Schema::string('last_page_url')
                    ->required()
                    ->nullable(false),
                Schema::string('next_page_url')
                    ->required()
                    ->nullable(),
                Schema::string('path')
                    ->required()
                    ->nullable(false),
                Schema::integer('per_page')
                    ->required()
                    ->nullable(false),
                Schema::string('prev_page_url')
                    ->required()
                    ->nullable(false),
                Schema::string('to')
                    ->required()
                    ->nullable(false),
                Schema::string('total')
                    ->maxLength(255)
                    ->required()
                    ->nullable(false),
            );
    }
}
