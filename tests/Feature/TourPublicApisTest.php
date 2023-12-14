<?php

use App\Models\Travel;
use Illuminate\Support\Carbon;
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
                        'page[size]' => PHP_INT_MAX, // Asking for a *really* big result page
                    ],
                ),
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
                        'page[size]' => PHP_INT_MAX, // Asking for a *really* big result page
                    ],
                ),
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
                        'page[size]' => PHP_INT_MAX, // Asking for a *really* big result page
                    ],
                ),
            )
            ->assertSuccessful()
            ->assertJsonIsObject();

        // Check response structure compliance
        expect($apiResponse->json())
            ->data->toBeArray()
            ->links->toBeArray()
            ->meta->toBeArray();

        // Check that in WS response there are ONLY tours matching the filtering..
        $filteringOperator = $filterName === 'priceFrom'
            ? '>='
            : '<=';
        $wsToursMatchingFiltering = collect($apiResponse->json('data'))
            ->where('price', $filteringOperator, $randomTour->price)
            ->pluck('price')
            ->count();
        expect($wsToursMatchingFiltering)->toBe(count($apiResponse->json('data')));

        // ...And that they are the same amount of DB ones, when applying the same filtering
        $filteredDatabaseTours = $travel->tours
            ->where('price', $filteringOperator, $randomTour->price)
            ->count();
        expect($filteredDatabaseTours)->toBe($wsToursMatchingFiltering);
    },
)->with('pricingFilters');

