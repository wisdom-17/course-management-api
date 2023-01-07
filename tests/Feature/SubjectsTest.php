<?php

use App\Models\CourseCalendar;
use App\Models\Subject;
use App\Models\SubjectDayTime;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->subjects = Subject::factory()
        ->has(Teacher::factory()->count(1))
        ->has(SubjectDayTime::factory()->count(1))
    ->count(3)->create();
    $this->courseCalendar = CourseCalendar::factory()->create();
});

test('list subjects returns an array of subjects objects', function () {
    $this->actingAs($this->user)
        ->get('/api/subjects')
        ->assertJson(fn (AssertableJson $json) => 
            $json->has('subjects', 3, fn ($json) =>
                $json
                    ->hasAll(['id', 'name', 'teachers', 'daysAndTimes', 'createdAt', 'updatedAt', 'deletedAt'])
                    ->has('teachers', 1, fn ($teacherJson) => 
                        $teacherJson->hasAll(['id', 'name', 'hourlyRate', 'createdAt', 'updatedAt', 'deletedAt'])
                    )
                    ->has('daysAndTimes', 1, fn ($daysAndTimesJson) => 
                        $daysAndTimesJson->hasAll(['id', 'subjectId','day', 'startTime', 'endTime', 'createdAt', 'updatedAt'])
                    )
            )            
        )
        ->assertStatus(200);
});

test('empty subject data throws validation error when saving', function () {
    $this->actingAs($this->user)
        ->postJson('/api/subjects')
        ->assertInvalid(['name', 'teacherIds', 'courseCalendarId', 'daysTimes']);
});

test('Subject, days and times saves to db when valid data provided', function () {

    $teacherIds = array();
    $teacherIds = $this->subjects->map(function ($subject) {
        return $subject->teachers()->pluck('teachers.id')->toArray();
    });
  
   $this->actingAs($this->user)
        ->postJson('/api/subjects', [
            'name' => 'Test Subject 1', 
            'courseCalendarId' => $this->courseCalendar->id,
            'teacherIds' => array_merge(...$teacherIds->toArray()),
            'daysTimes' => [
                ['day' => 'monday', 'startTime' => '09:00', 'endTime' => '10:30'],
                ['day' => 'wednesday', 'startTime' => '13:00', 'endTime' => '15:30'], 
                ['day' => 'friday', 'startTime' => '09:00', 'endTime' => '10:30'], 
            ],
        ])
        ->assertValid()
        ->assertCreated();
});

test('existing subject updates when valid subject data provided', function () {
    // $subjectId = $this->subjects->first()->id;
    // $this->actingAs($this->user)
    //     ->patchJson('/api/subjects/'.$subjectId, [
    //         'name' => 'Updated Subject Name',
    //         'courseCalendarId' => $this->courseCalendar->id,
    //         // 'teacherIds' => array_merge(...$teacherIds->toArray()),
    //         'daysTimes' => [
    //             ['day' => 'monday', 'startTime' => '09:00', 'endTime' => '10:30'],
    //             ['day' => 'wednesday', 'startTime' => '13:00', 'endTime' => '15:30'], 
    //             ['day' => 'friday', 'startTime' => '09:00', 'endTime' => '10:30'], 
    //         ],
    //     ])
    //     ->assertValid()
    //     ->assertOk();
});