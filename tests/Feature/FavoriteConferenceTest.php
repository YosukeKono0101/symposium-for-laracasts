<?php

use App\Models\Conference;
use App\Models\User;

test('it_favorites_a_conference', function () {
    $conference = Conference::factory()->create();

    $response = $this
        ->actingAs($user = User::factory()->create())
        ->post(route('conferences.favorite', ['conference' => $conference]));

    $this->assertCount(1, $user->favoritedConferences);
    $this->assertTrue($user->favoritedConferences->pluck('id')->contains($conference->id));
});

test('it_unfavorites_a_conference', function () {
    $conference = Conference::factory()->create();
    $user = User::factory()->create();

    $user->favoritedConferences()->attach($conference);

    $response = $this
        ->actingAs($user)
        ->delete(route('conferences.unfavorite', ['conference' => $conference]));

    $this->assertCount(0, $user->favoritedConferences);
});
