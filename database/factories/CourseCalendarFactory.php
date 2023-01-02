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
        $startDate = fake()->dateTimeBetween('next Monday', 'next Monday +7 days');
        $endDate = fake()->dateTimeBetween($startDate, $startDate->format('Y-m-d H:i:S').' +12 months');
        return [
            'name' => fake()->word().' Calendar',
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
        ];
    }
}