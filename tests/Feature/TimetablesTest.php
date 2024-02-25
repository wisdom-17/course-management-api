<?php

use App\Models\Course;
use App\Models\Subject;
use App\Models\SubjectDayTime;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->course = Course::factory()->create();

    $this->subjects = Subject::factory()
        ->for($this->course)
        ->has(Teacher::factory()->count(1))
        ->count(3)->create();
    
    SubjectDayTime::factory()->state(new Sequence(['day' => 'Monday'],['day' => 'Tuesday']))
        ->count(2)
        ->for($this->subjects->first())
        ->create();

    SubjectDayTime::factory()->state(['day' => 'Wednesday'])
        ->count(2)
        ->for($this->subjects[2])
        ->create();
    
    SubjectDayTime::factory()->state(['day' => 'Friday'])
        ->count(2)
        ->for($this->subjects[1])
        ->create();
    
});

test('Returns days of the week the subjects are taught', function () {
    $response = $this->actingAs($this->user)->get('/api/courses/'.$this->subjects->first()->course->id.'/timetable');

    $response->assertSeeInOrder([
        'Monday',
        'Tuesday',
        'Wednesday',
        'Friday',
    ]);

    $response
        ->assertJson(fn (AssertableJson $json) => 
            $json
                ->hasAll(['Monday', 'Tuesday', 'Wednesday', 'Friday'])
        )
        ->assertStatus(200);
});

test('Returns the subjects for each day that are taught in the course', function () {
    $response = $this->actingAs($this->user)->get('/api/courses/'.$this->subjects->first()->course->id.'/timetable');

    $response
        ->assertJson(fn (AssertableJson $json) => 
            $json
                ->has('Monday', 1)
                ->has('Tuesday', 1)
                ->has('Wednesday', 2)
                ->has('Friday', 2)
        )
        ->assertStatus(200);
});
