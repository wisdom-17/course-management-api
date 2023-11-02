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
        $startAndEndTime = $this->faker->startAndEndTime;
        return [
            'day' => $this->faker->dayOfWeek(),
            'start_time' => $startAndEndTime['start_time'],
            'end_time' => $startAndEndTime['end_time'],
        ];
    }
}
