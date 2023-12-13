<?php

use App\Models\Travel;

it(
    'test that all types of travel can be retrieved',
    function () {
        // Creating a new private travel, to make
        // sure we have at least one of them
        Travel::factory()->private()->createOne();

        // Fetching ALL available travels via public API
        // in order to check that private ones are
        // unavailable
        $apiResponse = $this
            ->actingAs($this->makeAdminUser())
            ->get(
                static::$PRIVATE_TRAVELS_API_PATH.'?page[size]='.PHP_INT_MAX, // Asking for a *really* big result page
            )
            ->assertSuccessful()
            ->assertJsonIsObject();

        $apiResponseTravelsCount = count($apiResponse->json('data'));
        $databaseTravelsCount = Travel::query()->count();
        expect($apiResponseTravelsCount === $databaseTravelsCount)->toBeTrue();
    },
);

it(
    'test that a travel can be created',
    function () {
        $travelData = Travel::factory()->makeOne();

        $apiResponse = $this
            ->actingAs($this->makeAdminUser())
            ->postJson(
                uri: static::$PRIVATE_TRAVELS_API_PATH,
                data: $travelData->toArray(),
                headers: [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            )
            ->assertCreated()
            ->assertJsonIsObject();

        expect($apiResponse->json())
            ->toHaveKeys([
                'id',
                'isPublic',
                'slug',
                'name',
                'description',
                'numberOfDays',
                'numberOfNights',
                'moods',
                'createdAt',
                'updatedAt',
            ])
            ->and($apiResponse->json('isPublic'))->toBe($travelData->isPublic)
            ->and($apiResponse->json('name'))->toBe($travelData->name)
            ->and($apiResponse->json('description'))->toBe($travelData->description)
            ->and($apiResponse->json('numberOfDays'))->toBe($travelData->numberOfDays)
            ->and($apiResponse->json('moods'))->toMatchArray($travelData->moods);
    },
);


it(
    'test that non-authenticated users cannot create new travels',
    function () {
        $this
            ->post(
                uri: static::$PRIVATE_TRAVELS_API_PATH,
                data: Travel::factory()->makeOne()->toArray(),
                headers: [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            )
            ->assertUnauthorized();
    },
);

