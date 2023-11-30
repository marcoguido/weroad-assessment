<?php

namespace App\Contracts\Models\Identifiers;

interface IdentifierContract
{
    /**
     * Crafts a new identifier value object
     */
    public static function make(string $value): static;
}
