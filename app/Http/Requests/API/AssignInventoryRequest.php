<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class AssignInventoryRequest extends FormRequest
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
            'event_id' => 'required|exists:events,id',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:inventories,id',
            'items.*.planned_quantity' => 'required|integer|min:0',
            'items.*.used' => 'nullable|integer|min:0', // Move 'used' inside 'items.*'
            'items.*.remaining' => 'nullable|integer|min:0', // Move 'remaining' inside 'items.*'
        ];
    }
}
