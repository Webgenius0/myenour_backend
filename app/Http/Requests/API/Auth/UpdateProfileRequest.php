<?php

namespace App\Http\Requests\API\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',

            'email' => [
              'nullable',
                'email',
                function ($attribute, $value, $fail) {
                    if ($this->user()->email !== $value) {
                        $fail('The email address is already in use.');
                    }
                },
            ],

            'phone'=>'nullable|string',
            'password' => 'nullable|string',
        ];
    }
}
