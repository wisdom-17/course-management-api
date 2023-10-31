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

test('List course returns an array of course calendar objects including their subjects', function () {
    $this->actingAs($this->user)
        ->get('/api/courses')
        ->assertJson(fn (AssertableJson $json) => 
            $json
                ->has(3)
                ->each(function ($course) {
                    $course
                        ->has('id')
                        ->has('name')
                        ->has('startDate')
                        ->has('endDate')
                        ->has('semesters')
                        ->has('dates')
                        ->has('createdAt')
                        ->has('updatedAt')
                        ->has('deletedAt')
                        ->has('subjects');
                })
        )
        ->assertStatus(200);
});