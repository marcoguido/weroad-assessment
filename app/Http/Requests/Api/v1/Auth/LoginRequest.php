<?php

namespace App\Http\Requests\Api\v1\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Deny invoking login route when already logged
        return ! Auth::check();
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ];
    }

    /**
     * Retrieves user credentials from request payload
     *
     * @return array<string, string>
     */
    public function getCredentials(): array
    {
        return $this->validated();
    }

    public function getEmail(): string
    {
        return $this->get('email');
    }
}
