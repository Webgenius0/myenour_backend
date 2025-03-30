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
            'inventory_id' => 'required|array|min:1',
            'inventory_id.*' => 'integer|exists:inventories,id',
            'event_name' => 'required|string|max:255',
            'start_event_date' => 'required|date|after_or_equal:today',
            'end_event_date' => 'required|date|after_or_equal:start_event_date',
            'total_event_days' => 'required|integer|min:1',
            'status' => 'required|in:upcoming,ongoing,completed',

        ];
    }

    


    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'inventory_id.required' => 'Inventory is required.',
            'inventory_id.*.exists' => 'One or more selected inventories do not exist.',
            'event_name.required' => 'Event name is required.',
            'start_event_date.required' => 'Start event date is required.',
            'start_event_date.after_or_equal' => 'Start event date must be today or later.',
            'end_event_date.required' => 'End event date is required.',
            'end_event_date.after_or_equal' => 'End event date must be on or after the start date.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be either upcoming, ongoing, or completed.',
        ];
    }
}
