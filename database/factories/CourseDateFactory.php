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
                $courseDate->start_date = fake()->dateTimeBetween(
                    \DateTime::createFromFormat('Y-m-d', $semester->courseCalendar->start_date),
                    (clone \DateTime::createFromFormat('Y-m-d', $semester->courseCalendar->start_date))->add(new \DateInterval('P1M'))
                )->format('Y-m-d');

                $courseDate->end_date = fake()->dateTimeBetween(
                    (clone \DateTime::createFromFormat('Y-m-d', $semester->courseCalendar->start_date))->add(new \DateInterval('P1M')), 
                    \DateTime::createFromFormat('Y-m-d', $semester->courseCalendar->end_date)
                )->format('Y-m-d');
            }

        });
    }
}
