<?php

namespace App\Models\Filters\Price;

class PriceToFilter extends AbstractPriceFilter
{
    protected function getFilterOperator(): string
    {
        return '<=';
    }
}
