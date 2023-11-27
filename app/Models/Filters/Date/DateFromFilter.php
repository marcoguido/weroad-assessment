<?php

namespace App\Models\Filters\Date;

class DateFromFilter extends AbstractDateFilter
{
    protected function getFilterOperator(): string
    {
        return '>=';
    }

    public function getColumnName(): string
    {
        return 'startingDate';
    }
}
