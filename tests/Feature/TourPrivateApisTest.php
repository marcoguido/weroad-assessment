<?php

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Tests\Feature\Constants\RouteName;

it(
    'test that all tours of a travel can be retrieved and they are sorted by starting date ASC',
    function () {
        /** @var Travel $travel */
        $travel = Travel::query()
            ->whereHas('tours')
            ->inRandomOrder()
            ->first();

        $apiResponse = $this
            ->actingAs($this->makeAdminUser())
            ->get(
                uri: route(
                    name: RouteName::PRIVATE_TRAVEL_TOURS_INDEX->value,
                    parameters: [
                        'travelId' => $travel->identifier,
                        'page[size]' => PHP_INT_MAX, // Manually bypass pagination
                    ],
                ),
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

        $originalWsTours = $apiResponse->json('data');
        $wsToursSortedByDate = collect($apiResponse->json('data'))
            ->sortBy(
                fn (array $tourData) => Carbon::parse($tourData['startingDate'])->timestamp,
            )
            ->pluck('id');

        foreach ($wsToursSortedByDate as $index => $sortedTour) {
            expect($originalWsTours[$index]['id'])->toBe($sortedTour);
        }
    },
);

it(
    'test that fetching tours of a non-existing travel (by ID) results in a 404 error',
    function () {
        $this
            ->actingAs($this->makeAdminUser())
            ->get(
                uri: route(
                    name: RouteName::PRIVATE_TRAVEL_TOURS_INDEX->value,
                    parameters: Str::slug(fake()->sentence),
                ),
                headers: [
                    'Accept' => 'application/json',
                ],
            )
            ->assertNotFound()
            ->assertJsonIsObject();
    },
);

it(
    'test that all tours of a travel can be retrieved and be sorted by price',
    function (string $sortingDirection) {
        /** @var Travel $travel */
        $travel = Travel::query()
            ->whereHas('tours')
            ->inRandomOrder()
            ->first();

        $apiResponse = $this
            ->actingAs($this->makeAdminUser())
            ->get(
                uri: route(
                    name: RouteName::PRIVATE_TRAVEL_TOURS_INDEX->value,
                    parameters: [
                        'travelId' => $travel->identifier,
                        'sort' => "{$sortingDirection}price",
                        'page[size]' => PHP_INT_MAX, // Manually bypass pagination
                    ],
                ),
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

        $originalWsTours = $apiResponse->json('data');
        $wsToursSortedByDate = collect($apiResponse->json('data'))
            ->sortBy(
                callback: 'price',
                descending: $sortingDirection === '-',
            )
            ->pluck('price');

        foreach ($wsToursSortedByDate as $index => $sortedTour) {
            // Comparing `prices` as there may be equally-priced tours
            expect($originalWsTours[$index]['price'])->toBe($sortedTour);
        }
    },
)->with('sortingDirections');

it(
    'test that tours of a travel can be filtered by baseline price',
    function (string $filterName) {
        /** @var Travel $travel */
        $travel = Travel::query()
            ->with('tours')
            ->whereHas('tours')
            ->inRandomOrder()
            ->first();
        $randomTour = $travel->tours->random();

        // Perform API call and save the response
        $apiResponse = $this
            ->actingAs($this->makeAdminUser())
            ->get(
                uri: route(
                    name: RouteName::PRIVATE_TRAVEL_TOURS_INDEX->value,
                    parameters: [
                        'travelId' => $travel->identifier,
                        "filter[$filterName]" => $randomTour->price,
                        'page[size]' => PHP_INT_MAX, // Manually bypass pagination
                    ],
                ),
                headers: [
                    'Accept' => 'application/json',
                ],
            )
            ->assertSuccessful()
            ->assertJsonIsObject();

        // Check response structure compliance
        expect($apiResponse->json())
            ->data->toBeArray()
            ->links->toBeArray()
            ->meta->toBeArray();

        $apiResponsePayload = collect($apiResponse->json('data'));

        // Check that in WS response there are ONLY tours matching the filtering...
        $filteringOperator = $filterName === 'priceFrom'
            ? '>='
            : '<=';
        $wsTourPricesMatchingFiltering = $apiResponsePayload
            ->where('price', $filteringOperator, $randomTour->price)
            ->pluck('price');
        expect($wsTourPricesMatchingFiltering)->toHaveCount($apiResponsePayload->count())
            ->and($wsTourPricesMatchingFiltering->diff($apiResponsePayload->pluck('price')))
            ->toHaveCount(0);

        // ...And that they are the same amount of DB ones, when applying the same filtering
        $filteredDatabaseTours = $travel
            ->tours
            ->where('price', $filteringOperator, $randomTour->price);
        expect($filteredDatabaseTours)->toHaveCount($wsTourPricesMatchingFiltering->count());

        // Finally, ensure default sorting (startingDate ASC) is applied
        $previousDate = null;
        $datesAreSequential = true;
        $apiResponsePayload
            ->each(function (array $tourData, int $loopIndex) use (&$previousDate, &$datesAreSequential) {
                if ($loopIndex === 0) {
                    $previousDate = Carbon::parse($tourData['startingDate']);
                }
                $tourDate = Carbon::parse($tourData['startingDate']);
                if ($tourDate->isBefore($previousDate)) {
                    $datesAreSequential = false;
                }
            });
        expect($datesAreSequential)->toBeTrue();
    },
)->with('pricingFilters');

it(
    'test that tours of a travel can be filtered by start date',
    function () {
        /** @var Travel $travel */
        $travel = Travel::query()
            ->with('tours')
            ->whereHas('tours')
            ->inRandomOrder()
            ->first();
        $randomTour = $travel->tours->random();

        // Perform API call and save the response
        $apiResponse = $this
            ->actingAs($this->makeAdminUser())
            ->get(
                uri: route(
                    name: RouteName::PRIVATE_TRAVEL_TOURS_INDEX->value,
                    parameters: [
                        'travelId' => $travel->identifier,
                        'filter[dateFrom]' => $randomTour->startingDate->toW3cString(),
                        'page[size]' => PHP_INT_MAX, // Manually bypass pagination
                    ],
                ),
                headers: [
                    'Accept' => 'application/json',
                ],
            )
            ->assertSuccessful()
            ->assertJsonIsObject();

        // Check response structure compliance
        expect($apiResponse->json())
            ->data->toBeArray()
            ->links->toBeArray()
            ->meta->toBeArray();

        $apiResponsePayload = collect($apiResponse->json('data'));

        // Check that in WS response there are ONLY tours matching the filtering...
        $wsToursMatchingFiltering = $apiResponsePayload
            ->filter(
                fn (array $tourData) => Carbon::parse($tourData['startingDate'])
                    ->greaterThanOrEqualTo($randomTour->startingDate),
            );
        expect($wsToursMatchingFiltering)->toHaveCount($apiResponsePayload->count());

        // ...And that they are the same amount of DB ones, when applying the same filtering
        $filteredDatabaseTours = $travel
            ->tours
            ->filter(
                fn (Tour $tour) => $tour->startingDate->greaterThanOrEqualTo($randomTour->startingDate),
            );
        expect($filteredDatabaseTours)->toHaveCount($wsToursMatchingFiltering->count());

        // Finally, ensure default sorting (startingDate ASC) is applied
        $previousDate = null;
        $datesAreSequential = true;
        $apiResponsePayload
            ->each(function (array $tourData, int $loopIndex) use (&$previousDate, &$datesAreSequential) {
                if ($loopIndex === 0) {
                    $previousDate = Carbon::parse($tourData['startingDate']);
                }
                $tourDate = Carbon::parse($tourData['startingDate']);
                if ($tourDate->isBefore($previousDate)) {
                    $datesAreSequential = false;
                }
            });
        expect($datesAreSequential)->toBeTrue();
    },
);

it(
    'test that tours of a travel can be filtered by end date',
    function () {
        /** @var Travel $travel */
        $travel = Travel::query()
            ->with('tours')
            ->whereHas('tours')
            ->inRandomOrder()
            ->first();
        $randomTour = $travel->tours->random();

        // Perform API call and save the response
        $apiResponse = $this
            ->actingAs($this->makeAdminUser())
            ->get(
                uri: route(
                    name: RouteName::PRIVATE_TRAVEL_TOURS_INDEX->value,
                    parameters: [
                        'travelId' => $travel->identifier,
                        'filter[dateTo]' => $randomTour->endingDate->toW3cString(),
                        'page[size]' => PHP_INT_MAX, // Manually bypass pagination
                    ],
                ),
                headers: [
                    'Accept' => 'application/json',
                ],
            )
            ->assertSuccessful()
            ->assertJsonIsObject();

        // Check response structure compliance
        expect($apiResponse->json())
            ->data->toBeArray()
            ->links->toBeArray()
            ->meta->toBeArray();

        $apiResponsePayload = collect($apiResponse->json('data'));

        // Check that in WS response there are ONLY tours matching the filtering...
        $wsToursMatchingFiltering = $apiResponsePayload
            ->filter(
                fn (array $tourData) => Carbon::parse($tourData['endingDate'])
                    ->lessThanOrEqualTo($randomTour->endingDate),
            );
        expect($wsToursMatchingFiltering)->toHaveCount($apiResponsePayload->count());

        // ...And that they are the same amount of DB ones, when applying the same filtering
        $filteredDatabaseTours = $travel
            ->tours
            ->filter(
                fn (Tour $tour) => $tour->endingDate->lessThanOrEqualTo($randomTour->endingDate),
            );
        expect($filteredDatabaseTours)->toHaveCount($wsToursMatchingFiltering->count());

        // Finally, ensure default sorting (startingDate ASC) is applied
        $previousDate = null;
        $datesAreSequential = true;
        $apiResponsePayload
            ->each(function (array $tourData, int $loopIndex) use (&$previousDate, &$datesAreSequential) {
                if ($loopIndex === 0) {
                    $previousDate = Carbon::parse($tourData['startingDate']);
                }
                $tourDate = Carbon::parse($tourData['startingDate']);
                if ($tourDate->isBefore($previousDate)) {
                    $datesAreSequential = false;
                }
            });
        expect($datesAreSequential)->toBeTrue();
    },
);

it(
    'test that new tours of a travel can be created',
    function () {
        /** @var Travel $travel */
        $travel = Travel::query()
            ->inRandomOrder()
            ->first();
        $tourData = Tour::factory()
            ->ofDays($travel->numberOfDays)
            ->makeOne([
                'travelId' => $travel->id,
            ]);

        // Perform API call and save the response
        $apiResponse = $this
            ->actingAs($this->makeAdminUser())
            ->postJson(
                uri: route(
                    name: RouteName::PRIVATE_TRAVEL_TOURS_CREATE->value,
                    parameters: $travel->identifier,
                ),
                data: $tourData->toArray(),
                headers: [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            )
            ->assertCreated()
            ->assertJsonIsObject();

        $serializedModel = $tourData->toArray();
        expect($apiResponse->json())
            ->toHaveKeys([
                'id',
                'travelId',
                'name',
                'startingDate',
                'endingDate',
                'price',
                'createdAt',
                'updatedAt',
            ])
            ->and($apiResponse->json('travelId'))->toBe($travel->id)
            ->and($apiResponse->json('name'))->toBe($serializedModel['name'])
            ->and($apiResponse->json('startingDate'))->toBe($serializedModel['startingDate'])
            ->and($apiResponse->json('endingDate'))->toBe($serializedModel['endingDate'])
            ->and($apiResponse->json('price'))->toBe($serializedModel['price']);
    },
);

it(
    'test that new tours cannot be created if duration is not compliant with parent travel',
    function () {
        $travel = Travel::factory()
            ->state([
                'numberOfDays' => 10,
            ])
            ->create();
        $tourData = Tour::factory()
            ->state([
                'startingDate' => Carbon::tomorrow(),
                'endingDate' => Carbon::tomorrow()->addDays(3), // Duration of 4 days, less than parent travel
            ])
            ->makeOne([
                'travelId' => $travel->id,
            ]);

        $this
            ->actingAs($this->makeAdminUser())
            ->postJson(
                uri: route(
                    name: RouteName::PRIVATE_TRAVEL_TOURS_CREATE->value,
                    parameters: $travel->identifier,
                ),
                data: $tourData->toArray(),
                headers: [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            )
            ->assertJsonValidationErrorFor('endingDate')
            ->assertJsonIsObject();
    },
);

it(
    'test that existing tours can be updated',
    function () {
        /** @var Travel $travel */
        $travel = Travel::query()
            ->with('tours')
            ->whereHas('tours')
            ->inRandomOrder()
            ->first();
        $tourToUpdate = $travel
            ->tours
            ->random();
        $tourToUpdate->name = 'An amazing tour, trust me!';

        // Perform API call and save the response
        $apiResponse = $this
            ->actingAs($this->makeAdminUser())
            ->patchJson(
                uri: route(
                    name: RouteName::PRIVATE_TRAVEL_TOURS_UPDATE->value,
                    parameters: $tourToUpdate->identifier,
                ),
                data: $tourToUpdate->toArray(),
                headers: [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            )
            ->assertAccepted()
            ->assertJsonIsObject();

        $serializedModel = $tourToUpdate->toArray();
        expect($apiResponse->json())
            ->toHaveKeys([
                'id',
                'travelId',
                'name',
                'startingDate',
                'endingDate',
                'price',
                'createdAt',
                'updatedAt',
            ])
            ->and($apiResponse->json('travelId'))->toBe($travel->id)
            ->and($apiResponse->json('name'))->toBe($serializedModel['name'])
            ->and($apiResponse->json('startingDate'))->toBe($serializedModel['startingDate'])
            ->and($apiResponse->json('endingDate'))->toBe($serializedModel['endingDate'])
            ->and($apiResponse->json('price'))->toBe($serializedModel['price']);
    },
);

it(
    'test that is not possible to update a tour with an invalid duration',
    function () {
        $travel = Travel::factory()
            ->state([
                'numberOfDays' => 10,
            ])
            ->has(Tour::factory())
            ->create();
        /** @var Tour $tour */
        $tour = $travel
            ->tours()
            ->first();
        $tour->startingDate = Carbon::tomorrow();
        $tour->endingDate = Carbon::tomorrow()->addDays(3); // Duration of 4 days, less than parent travel

        $this
            ->actingAs($this->makeAdminUser())
            ->postJson(
                uri: route(
                    name: RouteName::PRIVATE_TRAVEL_TOURS_CREATE->value,
                    parameters: $travel->identifier,
                ),
                data: $tour->toArray(),
                headers: [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            )
            ->assertJsonValidationErrorFor('endingDate')
            ->assertJsonIsObject();
    },
);

it(
    'test that trying to update a non-existing tour results in 404 error',
    function () {
        /** @var Travel $travel */
        $travel = Travel::query()
            ->with('tours')
            ->whereHas('tours')
            ->inRandomOrder()
            ->first();
        $tourToUpdate = $travel
            ->tours
            ->random();
        $tourToUpdate->name = 'An amazing tour, trust me!';

        $this
            ->actingAs($this->makeAdminUser())
            ->patchJson(
                uri: route(
                    name: RouteName::PRIVATE_TRAVEL_TOURS_UPDATE->value,
                    parameters: Str::uuid()->toString(),
                ),
                data: $tourToUpdate->toArray(),
                headers: [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            )
            ->assertNotFound()
            ->assertJsonIsObject();
    },
);

