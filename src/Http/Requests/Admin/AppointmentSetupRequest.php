<?php

namespace App\Modules\Appointment\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentSetupRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'appointment_type_id' => 'required|integer',
            'applicable_days' => 'required|array',
            'appointment_date' => 'required',
//            'effective_from' => 'required|date|date_format:m/d/Y',
//            'effective_to' => 'required|date|date_format:m/d/Y|after:effective_from',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'time_slot' => 'required|in:30,60',
            'available_slot' => 'required|integer|min:1',
        ];
        return $rules;
    }
}
