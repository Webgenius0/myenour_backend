<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
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

            'supplier_name' => 'required|string|max:255',
            'lead_time_days' => 'required|integer|max:255',
            'pack_size_constraint' => 'required|integer|min:10',
            'status' => 'required|in:pending,active',
        ];
    }

     /**
     * Get custom messages for validation errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'supplier_name.required' => 'The supplier name is required.',
            'lead_time_days.required' => 'The item provider is required.',
            'pack_size_constraint.required' => 'The order item is required.',
            'status.required' => 'The status is required.',
        ];
    }
}
