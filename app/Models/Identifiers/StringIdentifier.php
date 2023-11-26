<?php

namespace App\Models\Identifiers;

use App\Contracts\Models\Identifiers\IdentifierContract;

abstract class StringIdentifier implements IdentifierContract
{
    public function __construct(
        public readonly string $value,
    ) {
    }

    public static function make(string $value): static
    {
        return new static($value);
    }
}
