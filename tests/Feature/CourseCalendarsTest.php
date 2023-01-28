<?php

use App\Models\User;
use App\Models\CourseCalendar;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->courseCalendars = CourseCalendar::factory()->count(3)->create();
});

test('list course calendars returns an array of course calendar objects', function () {
    $this->actingAs($this->user)
        ->get('/api/course-calendars')
        ->assertJson(fn (AssertableJson $json) => 
            $json->has(3)
        )
        ->assertStatus(200);
});

test('empty course calendar data throws validation error when saving', function () {
    $this->actingAs($this->user)
        ->postJson('/api/course-calendars')
        ->assertInvalid(['name', 'startDate', 'endDate']);
});

test('Course Calendar and Semesters save to db when valid data provided', function () {  
    $this->actingAs($this->user)
         ->postJson('/api/course-calendars', [
             'name' => 'Test Course Calendar 1', 
             'startDate' => '2022-01-30',
             'endDate' => '2022-05-30',
             'semesters' => [
                 ['name' => 'Semester 1'],
                 ['name' => 'Semester 2'],
                 ['name' => 'Semester 3'],
             ],
         ])
         ->assertValid()
         ->assertCreated();
    $this->assertDatabaseCount('semesters', 3);
    $this->assertDatabaseCount('course_calendars', 4);

 });