<?php

namespace App\Actions\Tour;

use App\Models\Filters\Date\DateFromFilter;
use App\Models\Filters\Date\DateToFilter;
use App\Models\Filters\Price\PriceFromFilter;
use App\Models\Filters\Price\PriceToFilter;
use App\Models\Identifiers\TravelId;
use App\Models\Tour;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class GetPaginatedTravelTours
{
    public function __construct(
        private readonly Tour $model,
    ) {
    }

    /**
     * Retrieves all available Tour models bound to
     * Travel with matching ID
     *
     * @return LengthAwarePaginator<Tour>
     */
    public function execute(TravelId $travelId): LengthAwarePaginator
    {
        return QueryBuilder::for($this->model)
            ->where('travelId', '=', $travelId)
            ->allowedFilters([
                AllowedFilter::custom('priceFrom', new PriceFromFilter()),
                AllowedFilter::custom('priceTo', new PriceToFilter()),
                AllowedFilter::custom('dateFrom', new DateFromFilter()),
                AllowedFilter::custom('dateTo', new DateToFilter()),
            ])
            ->allowedSorts([
                AllowedSort::field('price'),
            ])
            ->defaultSort(AllowedSort::field('startingDate'))
            ->jsonPaginate();
    }
}
