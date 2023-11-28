<?php

namespace App\Http\Requests\Api\v1\Travels;

use App\Http\Requests\Api\v1\Travels\Data\TravelData;
use App\Rules\MoodFormatRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\WithData;

abstract class BaseTravelManipulationRequest extends FormRequest
{
    use WithData;

    /**
     * Determine if the user is authorized to make this request.
     */
    public abstract function authorize(): bool;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'isPublic' => 'required|boolean',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'numberOfDays' => 'required|int|min:1',
            'moods' => [
                'required',
                new MoodFormatRule(),
            ],
        ];
    }

    /**
     * Returns a DTO representing valuable data extracted
     * from request payload
     *
     * @return class-string<Data>
     */
    protected function dataClass(): string
    {
        return TravelData::class;
    }
}
