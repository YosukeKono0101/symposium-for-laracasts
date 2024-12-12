<?php

use App\Models\Talk;
use App\Models\User;

test('it lists talks on the talks index page', function () {
    $user = User::factory()
        ->has(Talk::factory()->count(2))
        ->create();

    $otherUsersTalk = Talk::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get(route('talks.index'))
        ->assertSee($user->talks->first()->title)
        ->assertDontSee($otherUsersTalk->title);

    $response->assertOk();
});

test('it shows basic talk details on the talk show page', function () {
    $talk = Talk::factory()->create();
    $talk->refresh();

    $response = $this
        ->actingAs($talk->author)
        ->get(route('talks.show', ['talk' => $talk]))
        ->assertSee($talk->title);

    $response->assertOk();
});

test('users cant see the talk show page for other talks', function () {
    $talk = Talk::factory()->create();
    $otherUser = User::factory()->create();

    $response = $this
        ->actingAs($otherUser)
        ->get(route('talks.show', ['talk' => $talk]))
        ->assertForbidden();
});
