<?php

namespace Database\Factories;

use App\Models\CourseDate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseDate>
 */
class CourseDateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => ucfirst(fake()->word()).' Dates',
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (CourseDate $courseDate) {
            if ($courseDate->start_date == '' && $courseDate->end_date == '') {
                $semester = $courseDate->semester;

                // get calendar start and end dates from semester or course calendar
                if ($semester) {
                    $calendarStartDate = $semester->courseCalendar->start_date;
                    $calendarEndDate = $semester->courseCalendar->end_date;
                } else {
                    $calendarStartDate = $courseDate->courseCalendar->start_date;
                    $calendarEndDate = $courseDate->courseCalendar->end_date;
                }

                $courseDate->start_date = fake()->dateTimeBetween(
                    \DateTime::createFromFormat('Y-m-d', $calendarStartDate),
                    (clone \DateTime::createFromFormat('Y-m-d', $calendarStartDate))->add(new \DateInterval('P1M'))
                )->format('Y-m-d');

                $courseDate->end_date = fake()->dateTimeBetween(
                    (clone \DateTime::createFromFormat('Y-m-d', $calendarStartDate))->add(new \DateInterval('P1M')), 
                    \DateTime::createFromFormat('Y-m-d', $calendarEndDate)
                )->format('Y-m-d');
            }

        });
    }
}
