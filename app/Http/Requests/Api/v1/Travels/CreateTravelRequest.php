<?php

namespace App\Http\Requests\Api\v1\Travels;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CreateTravelRequest extends BaseTravelManipulationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        return $currentUser->isAdmin();
    }
}
