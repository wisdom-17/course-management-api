<?php

// TODO: create a user, login and use logged in user to make the requests in the tests

test('returns an array of teacher objects', function () {
    // print_r($this->get('/api/teachers'));
    $response = $this->get('/api/teachers')->assertStatus(200);


});
