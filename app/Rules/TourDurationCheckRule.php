<?php

namespace App\Rules;

use App\Models\Travel;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Carbon;
use Illuminate\Translation\PotentiallyTranslatedString;

class TourDurationCheckRule implements DataAwareRule, ValidationRule
{
    /**
     * All the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    /**
     * Verifies that proposed Tour creation/update
     * ending date is compliant with Travel duration
     * constraint.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /** @var Travel $travel */
        $travel = Travel::query()->find($this->data['travelId']);

        $tourEndingDate = Carbon::parse($attribute);
        $tourStartingDate = Carbon::parse($this->data['startingDate']);
        $tourDuration = $tourEndingDate->diffInDays($tourStartingDate);

        if ($tourDuration != $travel->numberOfDays) {
            $fail(":attribute is invalid: proposed tour ending is not compliant with travel duration ({$travel->numberOfDays} days)");
        }
    }

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
