<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectRequest extends FormRequest
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
            'name' => 'required',
            'teacherId' => 'required|exists:teachers,id',
            'courseCalendarId' => 'required|exists:course_calendars,id',
            'daysTimes.*.day' => 'required_with_all:daysTimes.*.startTime, daysTimes.*.endTime',
            'daysTimes.*.startTime' => 'required_with_all:daysTimes.*.day, daysTimes.*.endTime',
            'daysTimes.*.endTime' => 'required_with_all:daysTimes.*.day, daysTimes.*.startTime',
        ];
    }
}
