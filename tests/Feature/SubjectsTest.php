<?php
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();

});

test('empty subject data throws validation error when saving', function () {
    $response = $this->actingAs($this->user)
        ->postJson('/api/subjects')
        ->assertInvalid(['name', 'teacherId', 'courseCalendarId']);
});
