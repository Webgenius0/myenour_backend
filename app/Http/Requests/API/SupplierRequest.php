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
            'item_provider' => 'required|string|max:255',
            'order_item' => 'required|integer|min:1',
            'order_date' => 'required|date',
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
            'item_provider.required' => 'The item provider is required.',
            'order_item.required' => 'The order item is required.',
            'order_date.required' => 'The order date is required.',
            'status.required' => 'The status is required.',
        ];
    }
}
