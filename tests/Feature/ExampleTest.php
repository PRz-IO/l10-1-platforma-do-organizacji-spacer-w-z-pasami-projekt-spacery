<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

// Ta linijka mówi Pestowi, żeby przed testem uruchomił migracje w bazie SQLite
uses(RefreshDatabase::class);

test('the application returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});