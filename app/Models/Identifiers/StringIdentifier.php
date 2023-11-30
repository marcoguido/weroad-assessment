<?php

namespace App\Models\Identifiers;

use App\Contracts\Models\Identifiers\IdentifierContract;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use Spatie\LaravelData\Casts\Uncastable;
use Spatie\LaravelData\Support\DataProperty;
use Stringable;

/**
 * @template T of Model
 */
abstract class StringIdentifier implements Castable, IdentifierContract, Stringable
{
    public function __construct(
        public readonly string $value,
    ) {
    }

    public static function make(string $value): static
    {
        return new static($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function dataCastUsing(...$arguments): Cast
    {
        return new class implements Cast
        {
            public function cast(DataProperty $property, mixed $value, array $context): mixed
            {
                if ($value === null) {
                    return Uncastable::create();
                }

                $identifierClass = array_keys(
                    $property->type->acceptedTypes,
                )[0];

                return new $identifierClass($value);
            }
        };
    }
}
