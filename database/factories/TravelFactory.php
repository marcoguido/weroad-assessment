<?php

namespace Database\Factories;

use App\Models\Travel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @extends Factory<Travel>
 */
class TravelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'isPublic' => $this->faker->boolean(),
            'name' => $this->faker->sentence(),
            'description' => $this->faker->realText(),
            'numberOfDays' => $this->faker->numberBetween(1, 20),
            'moods' => $this->makeRandomMoods(
                $this->faker->numberBetween(1, 10),
            ),
        ];
    }

    /**
     * Generates a `public` travel
     *
     * @return Factory<Travel>
     */
    public function public(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'isPublic' => true,
            ];
        });
    }

    /**
     * Generates a `private` travel
     *
     * @return Factory<Travel>
     */
    public function private(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'isPublic' => false,
            ];
        });
    }

    /**
     * @return Collection<integer, string>
     */
    private function makeRandomMoods(int $amount): Collection
    {
        $moods = collect();
        for ($moodIndex = 0; $moodIndex < $amount; $moodIndex++) {
            // Ensure no mood is generated twice
            do {
                $moodName = $this->faker->word();
            } while ($moods->has($moodName));
            $moodPercentage = $this->faker->numberBetween(1, 100);
            $moods->put($moodName, $moodPercentage);
        }

        return $moods;
    }
}
