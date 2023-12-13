<?php

use Illuminate\Support\Facades\File;

it(
    'test the application is running a successful response',
    fn () => $this->get('/')->assertOk(),
);

it(
    'test Swagger editor is available',
    fn () => $this->get('/swagger')->assertOk(),
);

it(
    'test OpenApi JSON file is available',
    fn () => expect(File::exists(public_path('openapi.json')))->toBeTrue(),
);
