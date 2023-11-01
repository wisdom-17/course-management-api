<?php

use App\Models\User;
use App\Models\Course;
use Database\Seeders\DateTypeSeeder;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->courses = Course::factory()->count(3)->create();
    $this->seed(DateTypeSeeder::class);
});

test('List course courses returns an array of course course objects', function () {
    $this->actingAs($this->user)
        ->get('/api/courses')
        ->assertJson(fn (AssertableJson $json) => 
            $json->has(3)
        )
        ->assertStatus(200);
});

test('Empty course course data throws validation error when saving', function () {
    $this->actingAs($this->user)
        ->postJson('/api/courses')
        ->assertInvalid(['name', 'startDate', 'endDate']);
});

test('Course Course, Semesters, and course dates save to db when valid data provided', function () {  
    $this->actingAs($this->user)
         ->postJson('/api/courses', [
            'name' => 'Test Course Course 1', 
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
    $this->assertDatabaseCount('courses', 4); // 4 including 3 from beforeEach
    $this->assertDatabaseCount('course_dates', 9);

 });

test('Existing Course Course updates when valid data provided', function () {
    $courseId = $this->courses->first()->id;
    $this->actingAs($this->user)
        ->patchJson('/api/courses/'.$courseId, [
            'name' => 'Updated Course Course Name',
            'startDate' => '2023-02-01',
            'endDate' => '2023-04-01'
        ])
        ->assertValid()
        ->assertOk();
});

test('Course Courses are soft deleted', function () {
    $courseIdA = $this->courses->first()->id;
    $courseIdB = $this->courses->last()->id;

    $this->actingAs($this->user)
        ->patchJson('/api/courses', [
            'courseIds' => [$courseIdA, $courseIdB]
        ])
        ->assertOk();
    
    $this->assertSoftDeleted($this->courses->first());
    $this->assertSoftDeleted($this->courses->last());
});