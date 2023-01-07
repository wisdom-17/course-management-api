<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubjectDayTime>
 */
class SubjectDayTimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $startDate = fake()->dateTimeBetween('next Tuesday', 'next Monday +7 days');
        $endDate = fake()->dateTimeBetween($startDate, $startDate->format('Y-m-d H:i:s').' +4 weeks');
        return [
            'day' => fake()->dayOfWeek(),
            'start_time' => $startDate->format('H:i:s'),
            'end_time' => $endDate->format('H:i:s'),
        ];
    }
}
