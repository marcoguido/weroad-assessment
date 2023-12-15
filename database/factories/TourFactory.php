<?php

namespace Database\Factories;

use App\Models\Tour;
use Carbon\CarbonImmutable;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tour>
 */
class TourFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    public function definition(): array
    {
        $tourStartingDate = CarbonImmutable::parse(
            $this->faker->dateTimeBetween(startDate: '-2 years', endDate: '+2 years'),
        );
        $tourEndingDate = $tourStartingDate->add(
            unit: $this->faker->randomElement(['day', 'week']),
            value: $this->faker->numberBetween(1, 5),
        );

        return [
            'name' => $this->faker->sentence(),
            'startingDate' => $tourStartingDate->format(Tour::DATE_FORMAT),
            'endingDate' => $tourEndingDate->format(Tour::DATE_FORMAT),
            'price' => $this->faker->numberBetween(int1: 100),
        ];
    }

    /**
     * Sets the duration of the tour
     *
     * @return Factory<Tour>
     */
    public function ofDays(int $amount): Factory
    {
        return $this->state(function (array $attributes) use ($amount) {
            $tourStartingDate = CarbonImmutable::parse('tomorrow');
            $tourEndingDate = $tourStartingDate->add(
                unit: 'day',
                value: $amount,
            );

            return [
                'startingDate' => $tourStartingDate->format(Tour::DATE_FORMAT),
                'endingDate' => $tourEndingDate->format(Tour::DATE_FORMAT),
            ];
        });
    }
}
