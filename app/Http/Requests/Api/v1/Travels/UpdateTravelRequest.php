<?php

namespace App\Http\Requests\Api\v1\Travels;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UpdateTravelRequest extends BaseTravelManipulationRequest
{
    public function authorize(): bool
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        return $currentUser->isEditor();
    }
}
