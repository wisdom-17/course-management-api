<?php

use App\Models\Subject;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->subjects = Subject::factory()->count(3)->create();
});

test('list subjects returns an array of subjects objects', function () {
    $response = $this->actingAs($this->user)
        ->get('/api/subjects')
        ->assertJson(fn (AssertableJson $json) => 
            $json->has(3)
        )
        ->assertStatus(200);
        // TODO: test for expected properties
});

test('empty subject data throws validation error when saving', function () {
    $response = $this->actingAs($this->user)
        ->postJson('/api/subjects')
        ->assertInvalid(['name', 'teacherId', 'courseCalendarId', 'daysTimes']);
});
