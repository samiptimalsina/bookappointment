<?php

namespace App\Modules\Appointment\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentTypeRequest extends FormRequest
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
            'name' => 'required|unique:appointment_types|max:255',
            'icon' => 'required|mimes:jpeg,jpg,png',
            'description' => 'nullable',
        ];
        if ($this->id) {
            $rules['name'] = 'required|max:255|unique:appointment_types,name,' . $this->id . ',id';
            $rules['icon'] = 'required|mimes:jpeg,jpg,png';
        }
        return $rules;
    }
}
