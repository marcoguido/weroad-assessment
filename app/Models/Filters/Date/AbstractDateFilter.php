<?php

namespace App\Models\Filters\Date;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

abstract class AbstractDateFilter implements Filter
{
    /**
     * Returns the operator of the specific filter implementation
     *
     * @return string
     */
    protected abstract function getFilterOperator(): string;

    /**
     * Returns the name of the column to be filtered
     *
     * @return string
     */
    protected abstract function getColumnName(): string;

    public function __invoke(Builder $query, $value, string $property)
    {
        return $query
            ->where(
                column: $this->getColumnName(),
                operator: $this->getFilterOperator(),
                value: $value,
            );
    }
}
