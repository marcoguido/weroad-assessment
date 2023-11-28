<?php

namespace App\Http\Requests\Api\v1\Tours;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UpdateTourRequest extends BaseTourManipulationRequest
{
    public function authorize(): bool
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        return $currentUser->isEditor();
    }
}
