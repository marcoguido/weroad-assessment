<?php

namespace App\Actions\Travel;

use App\Models\Travel;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

readonly class GetPaginatedTravels
{
    public function __construct(
        private Travel $model,
    ) {
    }

    /**
     * Retrieves all available Travel models
     *
     * @param  bool  $onlyPublic Whether to retrieve all Travels or only publicly available ones
     * @return LengthAwarePaginator<Travel>
     */
    public function execute(bool $onlyPublic = false): LengthAwarePaginator
    {
        return QueryBuilder::for($this->model)
            ->when(
                value: ($onlyPublic === true),
                callback: fn (Builder $query) => $query
                    ->where('isPublic', '=', true),
            )
            ->jsonPaginate();
    }
}
