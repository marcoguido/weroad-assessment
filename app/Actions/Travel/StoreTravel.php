<?php

namespace App\Actions\Travel;

use App\Http\Requests\Api\v1\Travels\Data\TravelData;
use App\Models\Travel;

class StoreTravel
{
    public function __construct(
        private readonly Travel $model,
    ) {
    }

    public function execute(TravelData $data): Travel
    {
        $travelModel = $this->model->newInstance(
            $data->toArray()
        );
        $travelModel->save();

        return $travelModel->refresh();
    }
}
