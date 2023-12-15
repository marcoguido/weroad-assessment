<?php

use App\Models\Travel;
use Tests\Feature\Constants\RouteName;

it(
    'test that all public travels can be retrieved',
    function () {
        $apiResponse = $this
            ->get(
                uri: route(RouteName::PUBLIC_TRAVELS_INDEX->value),
                headers: [
                    'Accept' => 'application/json',
                ],
            )
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
                uri: route(
                    name: RouteName::PUBLIC_TRAVELS_INDEX->value,
                    parameters: [
                        'page[size]' => PHP_INT_MAX, // Manually bypass pagination
                    ],
                ),
                headers: [
                    'Accept' => 'application/json',
                ],
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
