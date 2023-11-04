<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseDate;
use App\Models\DateType;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\SubjectDayTime;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
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
        Course::factory()
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
            ->has(
                Subject::factory()
                    ->count(3)
                    ->has(
                        SubjectDayTime::factory()
                            ->count(2)
                    )
                    ->has(
                        Teacher::factory()
                            ->count(1)
                    )
            )
            ->count(5)
            ->create();

        // without semesters
        Course::factory()
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
            ->has(
                Subject::factory()
                    ->count(3)
                    ->has(
                        SubjectDayTime::factory()
                            ->count(2)
                    )
                    ->has(
                        Teacher::factory()
                            ->count(1)
                    )
            )
            ->count(5)
            ->create();
    }
}
