<?php

use App\Models\Talk;
use App\Models\User;

test('a user can delete a talk', function () {
    $talk = Talk::factory()->create();

    $response = $this
        ->actingAs($talk->author)
        ->delete(route('talks.destroy', ['talk' => $talk]));

    $response
        ->assertRedirect(route('talks.index'));
});

test('a user cannot delete another users talk', function () {
    $talk = Talk::factory()->create();
    $otherUser = User::factory()->create();

    $response = $this
        ->actingAs($otherUser)
        ->delete(route('talks.destroy', ['talk' => $talk]));

    $response
        ->assertForbidden();
});
