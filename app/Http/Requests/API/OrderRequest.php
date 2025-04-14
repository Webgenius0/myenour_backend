<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
           'supplier_id'    => 'required|exists:suppliers,supplier_id',
            'item_id'        => 'required|exists:inventories,id',
            'order_quantity' => 'required|integer|min:1',
            'status'         => 'sometimes|in:Pending,Ordered,Received',
        ];
    }

     /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'supplier_id.required'    => 'Supplier is required.',
            'supplier_id.exists'      => 'The selected supplier does not exist.',
            'item_id.required'        => 'Item is required.',
            'item_id.exists'          => 'The selected item does not exist.',
            'order_quantity.required' => 'Order quantity is required.',
            'order_quantity.integer'  => 'Order quantity must be a valid number.',
            'order_quantity.min'      => 'Order quantity must be at least 1.',
            'status.in'               => 'Status must be either Pending, Ordered, or Received.',
        ];
    }
}
