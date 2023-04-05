<?php

use App\Models\User;
use App\Models\CourseCalendar;
use Database\Seeders\DateTypeSeeder;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->courseCalendars = CourseCalendar::factory()->count(3)->create();
    $this->seed(DateTypeSeeder::class);
});

test('List course calendars returns an array of course calendar objects', function () {
    $this->actingAs($this->user)
        ->get('/api/course-calendars')
        ->assertJson(fn (AssertableJson $json) => 
            $json->has(3)
        )
        ->assertStatus(200);
});

test('Empty course calendar data throws validation error when saving', function () {
    $this->actingAs($this->user)
        ->postJson('/api/course-calendars')
        ->assertInvalid(['name', 'startDate', 'endDate']);
});

test('Course Calendar, Semesters, and course dates save to db when valid data provided', function () {  
    $this->actingAs($this->user)
         ->postJson('/api/course-calendars', [
            'name' => 'Test Course Calendar 1', 
            'startDate' => '2022-01-30',
            'endDate' => '2022-08-30',
            'semesters' => [
                ['name' => 'Semester 1'],
                ['name' => 'Semester 2'],
                ['name' => 'Semester 3'],
            ],
            'terms' => [
                ['name' => 'Term 1', 'startDate' => '2022-01-30', 'endDate' => '2022-02-13', 'semester' => 'Semester 1'],
                ['name' => 'Term 2', 'startDate' => '2022-03-01', 'endDate' => '2022-03-30', 'semester' => 'Semester 2'],
                ['name' => 'Term 3', 'startDate' => '2022-05-01', 'endDate' => '2022-05-30', 'semester' => 'Semester 3'],
                ['name' => 'Term 4', 'startDate' => '2022-05-01', 'endDate' => '2022-05-30'],
                ['name' => 'Term 5', 'startDate' => '2022-06-01', 'endDate' => '2022-06-30'],
            ],
            'holidays' => [
                ['name' => 'Holiday 1', 'startDate' => '2022-06-01', 'endDate' => '2022-06-30', 'semester' => 'Semester 1'],
                ['name' => 'Holiday 2', 'startDate' => '2022-07-01', 'endDate' => '2022-07-30', 'semester' => 'Semester 2'],
                ['name' => 'Holiday 3', 'startDate' => '2022-08-01', 'endDate' => '2022-08-30', 'semester' => 'Semester 3'],
                ['name' => 'Holiday 4', 'startDate' => '2022-04-01', 'endDate' => '2022-04-30']
            ],
         ])
         ->assertValid()
         ->assertCreated();
    $this->assertDatabaseCount('semesters', 3);
    $this->assertDatabaseCount('course_calendars', 4); // 4 including 3 from beforeEach
    $this->assertDatabaseCount('course_dates', 9);

 });

test('Existing Course Calendar updates when valid data provided', function () {
    $courseCalendarId = $this->courseCalendars->first()->id;
    $this->actingAs($this->user)
        ->patchJson('/api/course-calendars/'.$courseCalendarId, [
            'name' => 'Updated Course Calendar Name',
            'startDate' => '2023-02-01',
            'endDate' => '2023-04-01'
        ])
        ->assertValid()
        ->assertOk();
});

test('Course Calendars are soft deleted', function () {
    $courseCalendarIdA = $this->courseCalendars->first()->id;
    $courseCalendarIdB = $this->courseCalendars->last()->id;

    $this->actingAs($this->user)
        ->patchJson('/api/course-calendars', [
            'courseCalendarIds' => [$courseCalendarIdA, $courseCalendarIdB]
        ])
        ->assertOk();
    
    $this->assertSoftDeleted($this->courseCalendars->first());
    $this->assertSoftDeleted($this->courseCalendars->last());
});