<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseCalendar>
 */
class CourseCalendarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $startDate = fake()->dateTimeBetween('now', '+7 days');
        $endDate = fake()->dateTimeBetween(
            $startDate->format('Y-m-d H:i:s').' +2 months', // ensure there is at least 2 months between start and end dates
            $startDate->format('Y-m-d H:i:s').' +12 months'
        );

        return [
            'name' => ucfirst(fake()->word()).' Course Calendar',
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
        ];
    }
}
