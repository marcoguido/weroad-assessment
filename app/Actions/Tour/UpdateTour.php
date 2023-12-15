<?php

namespace App\Actions\Tour;

use App\Http\Requests\Api\v1\Tours\Data\TourData;
use App\Models\Identifiers\TourId;
use App\Models\Tour;

readonly class UpdateTour
{
    public function __construct(
        private Tour $model,
    ) {
    }

    public function execute(TourId $tourId, TourData $data): Tour
    {
        /** @var Tour $tourModel */
        $tourModel = $this->model
            ->newQuery()
            ->findOrFail($tourId);
        $tourModel->update($data->toArray());

        return $tourModel->refresh();
    }
}
