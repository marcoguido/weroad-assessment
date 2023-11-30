<?php

namespace App\OpenApi\Schemas\Tours;

use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException;
use GoldSpecDigital\ObjectOrientedOAS\Objects\AllOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\AnyOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Not;
use GoldSpecDigital\ObjectOrientedOAS\Objects\OneOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\SchemaFactory;

abstract class TourSchema extends SchemaFactory
{
    /**
     * @return Schema[]
     */
    public abstract function getObjectProperties(): array;

    public abstract function getObjectId(): string;

    /**
     * @return AllOf|OneOf|AnyOf|Not|Schema
     * @throws InvalidArgumentException
     */
    public function build(): SchemaContract
    {
        return Schema::object($this->getObjectId())
            ->properties(
                ...$this->getObjectProperties(),
            );
    }
}
