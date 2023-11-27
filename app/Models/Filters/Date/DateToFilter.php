<?php

namespace App\Models\Filters\Date;

class DateToFilter extends AbstractDateFilter
{
    protected function getFilterOperator(): string
    {
        return '<=';
    }

    public function getColumnName(): string
    {
        return 'endingDate';
    }
}
