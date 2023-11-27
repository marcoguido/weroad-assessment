<?php

namespace App\Models\Identifiers;

use App\Contracts\Models\Identifiers\IdentifierContract;
use Illuminate\Database\Eloquent\Model;
use Stringable;

/**
 * @template T of Model
 */
abstract class StringIdentifier implements IdentifierContract, Stringable
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
}
