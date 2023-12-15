<?php

namespace App\Actions\Travel;

use App\Http\Requests\Api\v1\Travels\Data\TravelData;
use App\Models\Identifiers\TravelId;
use App\Models\Travel;

readonly class UpdateTravel
{
    public function __construct(
        private Travel $model,
    ) {
    }

    public function execute(TravelId $travelId, TravelData $data): Travel
    {
        /** @var Travel $travel */
        $travel = $this->model->newQuery()->find($travelId);
        $travel->update($data->toArray());

        return $travel->refresh();
    }
}
