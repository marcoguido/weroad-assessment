<?php

namespace App\Models\Concerns;

use App\Contracts\Models\Identifiers\IdentifierContract;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @template T of Model
 *
 * @property-read class-string<IdentifierContract<T>> $identifierClass
 * @property-read IdentifierContract<T> $identifier
 */
trait HasIdentifier
{
    public static function bootHasIdentifier(): void
    {
        if (! property_exists(static::class, 'identifierClass')) {
            throw new \RuntimeException('Missing `public static string $identifierClass = SomeClass::class;` from class definition');
        }
    }

    public function identifier(): Attribute
    {
        return Attribute::make(
            get: fn () => static::$identifierClass::make($this->getKey()),
        );
    }
}
