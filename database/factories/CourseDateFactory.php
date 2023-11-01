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

                // get course start and end dates from semester or course course
                if ($semester) {
                    $courseStartDate = $semester->course->start_date;
                    $courseEndDate = $semester->course->end_date;
                } else {
                    $courseStartDate = $courseDate->course->start_date;
                    $courseEndDate = $courseDate->course->end_date;
                }

                $courseDate->start_date = fake()->dateTimeBetween(
                    \DateTime::createFromFormat('Y-m-d', $courseStartDate),
                    (clone \DateTime::createFromFormat('Y-m-d', $courseStartDate))->add(new \DateInterval('P1M'))
                )->format('Y-m-d');

                $courseDate->end_date = fake()->dateTimeBetween(
                    (clone \DateTime::createFromFormat('Y-m-d', $courseStartDate))->add(new \DateInterval('P1M')), 
                    \DateTime::createFromFormat('Y-m-d', $courseEndDate)
                )->format('Y-m-d');
            }

        });
    }
}
