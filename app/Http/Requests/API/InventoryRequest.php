<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class InventoryRequest extends FormRequest
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
           'supplier_id' => 'required|integer|exists:suppliers,supplier_id',
            'item_name' => 'required|string|max:255',
            'current_quantity' => 'required|integer|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'max_stock_level' => 'required|integer|gte:min_stock_level',
            'pack_size' => 'required|integer|min:1'
        ];

    }

    /**
     * Get the custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'supplier_id.required' => 'The supplier ID is required.',
            'supplier_id.integer' => 'The supplier ID must be a valid integer.',
            'supplier_id.exists' => 'The selected supplier does not exist.',

            'item_name.required' => 'The item name is required.',
            'item_name.string' => 'The item name must be a valid string.',
            'item_name.max' => 'The item name should not exceed 255 characters.',

            'current_quantity.required' => 'The current quantity is required.',
            'current_quantity.integer' => 'The current quantity must be a valid integer.',
            'current_quantity.min' => 'The current quantity cannot be negative.',

            'min_stock_level.required' => 'The minimum stock level is required.',
            'min_stock_level.integer' => 'The minimum stock level must be an integer.',
            'min_stock_level.min' => 'The minimum stock level cannot be negative.',

            'max_stock_level.required' => 'The maximum stock level is required.',
            'max_stock_level.integer' => 'The maximum stock level must be an integer.',
            'max_stock_level.gte' => 'The maximum stock level must be greater than or equal to the minimum stock level.',

            'pack_size.required' => 'The pack size is required.',
            'pack_size.integer' => 'The pack size must be a valid integer.',
            'pack_size.min' => 'The pack size must be at least 1.',
        ];
    }
}
