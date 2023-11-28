<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class MoodFormatRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_array($value)) {
            $fail(':attribute must be an array');

            return;
        }

        $moodNames = array_keys($value);
        $moodValues = array_values($value);

        foreach ($moodNames as $index => $moodName) {
            if (! is_string($moodName)) {
                $fail("All :attribute keys must be strings, the one at index $index is invalid");
            }
        }

        foreach ($moodValues as $index => $moodValue) {
            if (! is_int($moodValue)) {
                $fail("All :attribute values must be integer numbers, the one at index $index is invalid");
            }

            if ($moodValue < 0) {
                $fail("All :attribute values must be non-negative numbers, the one at index $index is invalid");
            }
        }
    }
}
