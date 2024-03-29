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

        $timetable = $this->getSubjectsForEachDay();
        return $timetable;
    }


    private function getSubjectsForEachDay()
    {
        // 1. Group the subjects by day
        $groupedSubjects = $this->course->subjects->flatMap(function ($subject) {
            return $subject->subjectDayTimes->map(function ($dayTime) use ($subject) {
                return [
                    'day' => $dayTime->day,
                    'subject' => $subject
                ];
            });
        // 2. Group by day + sort the days by the order of the week 
        })->groupBy('day')->sortBy(function ($subjects, $day) {
            return array_search($day, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
        });

        return $groupedSubjects;
    }
}