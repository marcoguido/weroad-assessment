<?php

namespace App\Models\Filters\Price;

class PriceFromFilter extends AbstractPriceFilter
{
    protected function getFilterOperator(): string
    {
        return '>=';
    }
}
