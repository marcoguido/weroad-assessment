<?php

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Tests\Feature\Constants\RouteName;

it(
    'test that all tours of a public travel can be retrieved and they are sorted by starting date ASC',
    function () {
        /** @var Travel $travel */
        $travel = Travel::query()
            ->whereHas('tours')
            ->where('isPublic', '=', true)
            ->first();

        $apiResponse = $this
            ->get(
                uri: route(
                    name: RouteName::PUBLIC_TRAVEL_TOURS_INDEX->value,
                    parameters: [
                        'travel' => $travel->slug,
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
    'test that fetching tours of a non-existing travel (by slug) results in a 404 error',
    function () {
        $this
            ->get(
                uri: route(
                    name: RouteName::PUBLIC_TRAVEL_TOURS_INDEX->value,
                    parameters: Str::uuid()->toString(),
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
    'test that all tours of a public travel can be retrieved and be sorted by price',
    function (string $sortingDirection) {
        /** @var Travel $travel */
        $travel = Travel::query()
            ->whereHas('tours')
            ->where('isPublic', '=', true)
            ->first();

        $apiResponse = $this
            ->get(
                uri: route(
                    name: RouteName::PUBLIC_TRAVEL_TOURS_INDEX->value,
                    parameters: [
                        'travel' => $travel->slug,
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
    'test that public tours of a travel can be filtered by baseline price',
    function (string $filterName) {
        /** @var Travel $travel */
        $travel = Travel::query()
            ->with('tours')
            ->whereHas('tours')
            ->where('isPublic', '=', true)
            ->first();
        $randomTour = $travel->tours->random();

        // Perform API call and save the response
        $apiResponse = $this
            ->get(
                uri: route(
                    name: RouteName::PUBLIC_TRAVEL_TOURS_INDEX->value,
                    parameters: [
                        'travel' => $travel->slug,
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
    'test that public tours of a travel can be filtered by start date',
    function () {
        /** @var Travel $travel */
        $travel = Travel::query()
            ->with('tours')
            ->whereHas('tours')
            ->where('isPublic', '=', true)
            ->first();
        $randomTour = $travel->tours->random();

        // Perform API call and save the response
        $apiResponse = $this
            ->get(
                uri: route(
                    name: RouteName::PUBLIC_TRAVEL_TOURS_INDEX->value,
                    parameters: [
                        'travel' => $travel->slug,
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
    'test that public tours of a travel can be filtered by end date',
    function () {
        /** @var Travel $travel */
        $travel = Travel::query()
            ->with('tours')
            ->whereHas('tours')
            ->where('isPublic', '=', true)
            ->first();
        $randomTour = $travel->tours->random();

        // Perform API call and save the response
        $apiResponse = $this
            ->get(
                uri: route(
                    name: RouteName::PUBLIC_TRAVEL_TOURS_INDEX->value,
                    parameters: [
                        'travel' => $travel->slug,
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
