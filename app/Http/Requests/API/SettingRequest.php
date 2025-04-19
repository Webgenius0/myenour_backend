<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            //
        ];
    }
    // public function messages()
    // {
    //     return [
    //         'image.required' => 'The image field is required.',
    //         'image.image' => 'The file must be an image.',
    //         'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
    //         'image.max' => 'The image may not be greater than 2048 kilobytes.',
    //     ];
    // }
}
