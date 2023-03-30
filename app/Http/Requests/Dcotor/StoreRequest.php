<?php

namespace App\Http\Requests\Dcotor;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\TimeAvailability;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'doctor_id' => ['required', 'exists:doctors,id', function ($attribute, $value, $fail) {
                if (TimeAvailability::where('doctor_id', $value)->exists()) {
                    $fail("For this doctor time availability already added please change.");
                }
            }],
            'days' => ['required', 'array', 'min:1'],
        ];
    }
    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'doctor_id' => 'doctor',
        ];
    }
}
