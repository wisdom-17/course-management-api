<?php

namespace App\Http\Requests;

use App\Models\Teacher;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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

        $existingTeacherIds = Teacher::pluck('id')->toArray();
        return [
            'name' => 'required',
            'teacherIds' => 'required|array',
            'teacherIds.*' => Rule::in($existingTeacherIds),
            'courseCalendarId' => 'required|exists:course_calendars,id',
            'daysTimes' => 'required|array|min:1',
            'daysTimes.*.day' => 'required',
            'daysTimes.*.startTime' => 'required',
            'daysTimes.*.endTime' => 'required',
        ];
    }
}
