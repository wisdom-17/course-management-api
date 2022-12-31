<?php
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->teachers = Teacher::factory()->count(3)->create();

});

test('list teachers returns an array of teacher objects', function () {
    $response = $this->actingAs($this->user)
        ->get('/api/teachers')
        ->assertJson(fn (AssertableJson $json) => 
            $json->has(3)
        )
        ->assertStatus(200);
});

test('empty teacher data throws validation error when saving', function () {
    $response = $this->actingAs($this->user)
        ->postJson('/api/teachers')
        ->assertInvalid(['name', 'hourlyRate']);
});

test('teacher saves successfully to db when valid teacher data provided', function () {
    $response = $this->actingAs($this->user)
        ->postJson('/api/teachers', ['name' => 'Test Teacher 1', 'hourlyRate' => 25.00])
        ->assertValid()
        ->assertCreated();
});