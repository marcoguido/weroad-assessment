<?php

it(
    'test the application is running a successful response',
    fn () => $this->get('/')->assertOk(),
);

it(
    'test Swagger editor is available',
    fn () => $this->get('/swagger')->assertOk(),
);
