<?php

namespace App\Actions\Travel;

use App\Http\Requests\Api\v1\Travels\Data\TravelData;
use App\Models\Identifiers\TravelId;
use App\Models\Travel;

class UpdateTravel
{
    public function __construct(
        private readonly Travel $model,
    ) {
    }

    public function execute(TravelId $travelId, TravelData $data): Travel
    {
        $travel = $this->model->newQuery()->find($travelId);
        $travel->update($data->toArray());

        return $travel->refresh();
    }
}
