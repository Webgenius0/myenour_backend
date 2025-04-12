<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class DailyTrackingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_id' => 'required|exists:events,id',
            'day_number' => 'required|string|min:1',

            // Allow both single item object or array of objects
            'items' => ['required'],
            'items.item_id' => 'required_without:items.0.item_id|exists:inventories,id',
            'items.picked' => 'nullable|integer|min:0',
            'items.end_of_day' => 'nullable|integer|min:0',
            'items.used' => 'nullable|integer|min:0',
            'items.remaining_start' => 'nullable|integer|min:0',
            'items.remaining_end' => 'nullable|integer|min:0',

            'items.*.item_id' => 'required|exists:inventories,id',
            'items.*.picked' => 'nullable|integer|min:0',
            'items.*.end_of_day' => 'nullable|integer|min:0',
            'items.*.used' => 'nullable|integer|min:0',
            'items.*.remaining_start' => 'nullable|integer|min:0',
            'items.*.remaining_end' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'event_id.required' => 'Event ID is required',
            'event_id.exists' => 'Event ID must exist in the events table',
            'day_number.required' => 'Day number is required',
            'day_number.integer' => 'Day number must be an string',

            'items.required' => 'At least one tracking item is required',
            'items.array' => 'Items must be an array or a valid item object',

            'items.item_id.required_without' => 'Item ID is required',
            'items.item_id.exists' => 'Item ID must exist in inventories table',
            'items.picked.integer' => 'Picked must be an integer',
            'items.end_of_day.integer' => 'End of day must be an integer',

            'items.*.item_id.required' => 'Item ID is required',
            'items.*.item_id.exists' => 'Item ID must exist in inventories table',
            'items.*.picked.integer' => 'Picked must be an integer',
            'items.*.end_of_day.integer' => 'End of day must be an integer',
        ];
    }

    protected function prepareForValidation(): void
    {

        if (isset($this->items) && isset($this->items['item_id'])) {
            $this->merge([
                'items' => [$this->items]
            ]);
        }
    }
}
