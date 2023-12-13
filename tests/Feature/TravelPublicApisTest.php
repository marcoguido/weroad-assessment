<?php

use App\Models\Travel;

it(
    'test that all public travels can be retrieved',
    function () {
        $apiResponse = $this
            ->get(static::$PUBLIC_TRAVEL_INDEX_API)
            ->assertSuccessful()
            ->assertJsonIsObject();
    },
);
