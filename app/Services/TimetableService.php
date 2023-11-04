<?php

namespace App\Services;

class TimetableService
{
    private $course;

    public function generateTimetable($course)
    {
        if ($course) {
            $this->course = $course;
        } else {
            throw new \Exception('Invalid course for timetable generation');
        }

        // 1) Get the days the subject(s) are taught on
        $days = $this->getDays();

        $timetable = array_fill_keys($days, []);
        return $timetable;
    }

    private function getDays()
    {
        $days = $this->course->subjects->map(function ($subject) {
            return $subject->subjectDayTimes->map(function ($dayTime) {
                return $dayTime->day;
            });
        })->flatten()->unique()->toArray();

        return $days;
    }
}