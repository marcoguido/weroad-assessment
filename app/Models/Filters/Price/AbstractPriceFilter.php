<?php

namespace App\Models\Filters\Price;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

abstract class AbstractPriceFilter implements Filter
{
    /**
     * Returns the operator of the specific filter implementation
     *
     * @return string
     */
    protected abstract function getFilterOperator(): string;

    public function __invoke(Builder $query, $value, string $property)
    {
        return $query
            ->where(
                column: 'price',
                operator: $this->getFilterOperator(),
                value: $value,
            );
    }
}
