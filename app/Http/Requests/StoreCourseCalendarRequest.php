<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseCalendarRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'semesters' => 'array|sometimes',
            'terms' => 'array|min:1',
            'terms.*.semester' => 'max:255',
            'terms.*.name' => 'max:255',
            'terms.*.startDate' => 'date',
            'terms.*.endDate' => 'date',
            'holidays' => 'array|min:1',
            'holidays.*.semester' => 'max:255',
            'holidays.*.name' => 'max:255',
            'holidays.*.startDate' => 'date',
            'holidays.*.endDate' => 'date',

        ];
    }
}
