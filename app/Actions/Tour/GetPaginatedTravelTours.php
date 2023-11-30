<?php

namespace App\Actions\Tour;

use App\Models\Filters\Date\DateFromFilter;
use App\Models\Filters\Date\DateToFilter;
use App\Models\Filters\Price\PriceFromFilter;
use App\Models\Filters\Price\PriceToFilter;
use App\Models\Identifiers\TravelId;
use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class GetPaginatedTravelTours
{
    public function __construct(
        private readonly Tour $model,
        private readonly Travel $travelModel,
    ) {
    }

    /**
     * Retrieves all available Tour models bound to
     * Travel with matching ID
     *
     * @return LengthAwarePaginator<Tour>
     */
    public function execute(TravelId $travelId, bool $publicTravelToursOnly = false): LengthAwarePaginator
    {
        /** @var Travel $travel */
        $travel = $this->travelModel::query()->findOrFail($travelId);

        if (
            $publicTravelToursOnly
            && ! $travel->isPublic
        ) {
            throw (new ModelNotFoundException())
                ->setModel(
                    Travel::class,
                    $travelId,
                );
        }

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
