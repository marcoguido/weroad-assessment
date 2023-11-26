<?php

namespace App\Contracts\Models\Identifiers;

interface IdentifierContract
{
    /**
     * Crafts a new identifier value object
     *
     * @param string $value
     * @return static
     */
    public static function make(string $value): static;
}
