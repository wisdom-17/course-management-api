<?php
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->teachers = Teacher::factory()->count(3)->create();

});

test('returns an array of teacher objects', function () {
    $response = $this->actingAs($this->user)
        ->get('/api/teachers')
        ->assertJson(fn (AssertableJson $json) => 
            $json->has(3)
        )
        ->assertStatus(200);
});
