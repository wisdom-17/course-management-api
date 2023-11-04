<?php

namespace App\Services;

use App\Models\Course;

class TimetableService
{
    public function generateTimetable($courseId)
    {
        $days = [];
        // Get the course
        $course = Course::find($courseId);

        // loop through each subject and get the day
        foreach ($course->subjects as $subject) {
            // 
    }
}