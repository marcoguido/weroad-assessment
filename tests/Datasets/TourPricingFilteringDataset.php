<?php

dataset(
    name: 'pricingFilters',
    dataset: fn () => [
        'FROM' => 'priceFrom',
        'UP TO' => 'priceTo',
    ],
);
