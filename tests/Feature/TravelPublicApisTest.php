<?php

use App\Models\Travel;

it(
    'test that all public travels can be retrieved',
    function () {
        $apiResponse = $this
            ->get(static::$PUBLIC_TRAVELS_API_PATH)
            ->assertSuccessful()
            ->assertJsonIsObject();

        expect($apiResponse->json())
            ->data->toBeArray()
            ->links->toBeArray()
            ->meta->toBeArray();
    },
);

it(
    'test that private travels cannot be retrieved via public API',
    function () {
        // Creating a new private travel, to make
        // sure we have at least one of them
        Travel::factory()->private()->createOne();

        // Fetching ALL available travels via public API
        // in order to check that private ones are
        // unavailable
        $apiResponse = $this
            ->get(
                static::$PUBLIC_TRAVELS_API_PATH.'?page[size]='.PHP_INT_MAX, // Asking for a *really* big result page
            )
            ->assertSuccessful()
            ->assertJsonIsObject();

        $privateTravel = collect($apiResponse->json('data'))
            ->first(
                fn (array $travelResponseObject) => ! ((bool) $travelResponseObject['isPublic']),
            );

        expect($privateTravel)->toBeNull();
    },
);
