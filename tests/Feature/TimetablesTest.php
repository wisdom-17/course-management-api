<?php

use App\Models\Course;
use App\Models\Subject;
use App\Models\SubjectDayTime;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->course = Course::factory()->create();

    $this->subjects = Subject::factory()
        ->for($this->course)
        ->has(Teacher::factory()->count(1))
        ->count(3)->create();
    
    SubjectDayTime::factory()->state(['day' => 'Monday'])
        ->count(1)
        ->for($this->subjects->first())
        ->create();

    SubjectDayTime::factory()->state(['day' => 'Tuesday'])
        ->count(1)
        ->for($this->subjects[1])
        ->create();

    SubjectDayTime::factory()->state(['day' => 'Wednesday'])
        ->count(1)
        ->for($this->subjects[2])
        ->create();
    
});

test('Returns days of the week the subjects are taught', function () {
    $response = $this->actingAs($this->user)->get('/api/courses/'.$this->subjects->first()->course->id.'/timetable');

    $response
        ->assertJson(fn (AssertableJson $json) => 
            $json
                ->hasAll(['Monday', 'Tuesday', 'Wednesday'])
        )
        ->assertStatus(200);
});
