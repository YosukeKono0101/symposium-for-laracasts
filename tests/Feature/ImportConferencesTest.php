<?php

use App\Console\Commands\ImportConferences;
use App\Models\Conference;

test('it imports a conference', function () {
    $command = new ImportConferences;

    $data = [
        'name' => 'This is the name from the API',
        '_rel' => ['cfp_uri' => 'v1/cfp/osof32dadsa2eo'],
    ];

    $command->importOrUpdateConference($data);

    $first = Conference::first();
    $this->assertEquals($first->title, $data['name']);
});

test('it updates a conference', function () {
    $command = new ImportConferences;

    Conference::create(['title' => 'Original DB title', 'callingallpapers_id' => 'v1/cfp/osof32dadsa2eo']);

    $data = [
        'name' => 'This is the name from the API',
        '_rel' => ['cfp_uri' => 'v1/cfp/osof32dadsa2eo'],
    ];

    $command->importOrUpdateConference($data);

    $first = Conference::first();
    $this->assertEquals($first->title, $data['name']);
    $this->assertEquals(1, Conference::count());
});
