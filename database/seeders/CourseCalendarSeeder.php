<?php

namespace Database\Seeders;

use App\Models\CourseCalendar;
use App\Models\CourseDate;
use App\Models\DateType;
use App\Models\Semester;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class CourseCalendarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $termDateType = DateType::where('type', 'term')->first();
        $holidayDateType = DateType::where('type', 'holiday')->first();

        // with semesters
        CourseCalendar::factory()
            ->has(
                Semester::factory()
                ->has(
                    CourseDate::factory()
                        ->count(2)
                        ->sequence(fn (Sequence $sequence) => 
                            $sequence->index === 0
                                ? [
                                    'name' => ucfirst(fake()->word()).' Term date', 
                                    'date_type_id' => $termDateType->id, 
                                ]
                                : [
                                    'name' => ucfirst(fake()->word()).' Holiday date', 
                                    'date_type_id' => $holidayDateType->id,
                                ]
                        )
                )
                ->count(2)
            )
            ->count(5)
            ->create();

        // without semesters
        CourseCalendar::factory()
            ->has(
                CourseDate::factory()
                    ->count(2)
                    ->sequence(fn (Sequence $sequence) => 
                        $sequence->index === 0
                            ? [
                                'name' => ucfirst(fake()->word()).' Term date', 
                                'date_type_id' => $termDateType->id, 
                            ]
                            : [
                                'name' => ucfirst(fake()->word()).' Holiday date', 
                                'date_type_id' => $holidayDateType->id,
                            ]
                    )
            )
            ->count(5)
            ->create();
    }
}
