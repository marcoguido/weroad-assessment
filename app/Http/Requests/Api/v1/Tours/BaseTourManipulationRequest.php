<?php

namespace App\Http\Requests\Api\v1\Tours;

use App\Http\Requests\Api\v1\Tours\Data\TourData;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\WithData;

abstract class BaseTourManipulationRequest extends FormRequest
{
    use WithData;

    /**
     * Determine if the user is authorized to make this request.
     */
    abstract public function authorize(): bool;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'travelId' => 'required|uuid|exists:travels,id',
            'name' => 'required|string|max:255',
            'startingDate' => 'required|date|after:now',
            'endingDate' => 'required|date|after:startingDate',
            'price' => 'required|integer|min:0',
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
        return TourData::class;
    }
}
