<?php

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->subjects = Subject::factory()
        ->has(Teacher::factory()->count(1))
        ->count(3)->create();
});

test('list subjects returns an array of subjects objects', function () {
    $response = $this->actingAs($this->user)
        ->get('/api/subjects')
        ->assertJson(fn (AssertableJson $json) => 
            $json->has('subjects', 3, fn ($json) =>
                $json->hasAll(['id', 'name', 'teachers', 'createdAt', 'updatedAt', 'deletedAt'])
                    ->has('teachers', 1, fn ($teacherJson) => 
                        $teacherJson->hasAll(['id', 'name', 'hourlyRate', 'createdAt', 'updatedAt', 'deletedAt'])
                    )
            )            
        )
        ->assertStatus(200);
});

test('empty subject data throws validation error when saving', function () {
    $response = $this->actingAs($this->user)
        ->postJson('/api/subjects')
        ->assertInvalid(['name', 'teacherId', 'courseCalendarId', 'daysTimes']);
});
