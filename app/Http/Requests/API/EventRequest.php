<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
           'event_category_id' => 'nullable|integer|exists:event_categories,id',
            'event_name' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'number_of_days' => 'required|integer|min:1',
            'status' => 'required|in:upcoming,ongoing,completed',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|integer|exists:inventories,id',
            'items.*.planned_quantity' => 'required|integer|min:1',
            'items.*.used' => 'nullable|integer|min:0',
            'items.*.remaining' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'event_category_id.required' => 'Event category is required.',
            'event_category_id.integer' => 'Event category must be a valid integer.',
            'event_category_id.exists' => 'Event category must exist.',
            'event_name.required' => 'Event name is required.',
            'event_name.string' => 'Event name must be a string.',
            'event_name.max' => 'Event name cannot be longer than 255 characters.',
            'start_date.required' => 'Start event date is required.',
            'start_date.date' => 'Start date must be a valid date.',
            'start_date.after_or_equal' => 'Start event date must be today or later.',
            'number_of_days.required' => 'Number of days is required.',
            'number_of_days.integer' => 'Number of days must be a valid integer.',
            'number_of_days.min' => 'Number of days must be at least 1.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be either "upcoming", "ongoing", or "completed".',
            'items.required' => 'At least one item is required.',
            'items.*.item_id.required' => 'Item ID is required.',
            'items.*.item_id.exists' => 'Item must exist in inventory.',
            'items.*.planned_quantity.required' => 'Planned quantity is required.',
            'items.*.planned_quantity.min' => 'Planned quantity must be at least 1.',
        ];
    }
}
