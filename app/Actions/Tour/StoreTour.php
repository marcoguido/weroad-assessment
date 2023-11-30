<?php

namespace App\Actions\Tour;

use App\Http\Requests\Api\v1\Tours\Data\TourData;
use App\Models\Tour;

class StoreTour
{
    public function __construct(
        private readonly Tour $model,
    ) {
    }

    public function execute(TourData $data): Tour
    {
        $tourModel = $this->model->newInstance(
            $data->toArray(),
        );
        $tourModel->save();

        return $tourModel->refresh();
    }
}
