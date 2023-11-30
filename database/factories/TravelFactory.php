<?php

namespace Database\Factories;

use App\Models\Travel;
use Illuminate\Database\Eloquent\Factories\Factory;

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

    private function makeRandomMoods(int $amount): array
    {
        $moods = [];
        for ($moodIndex = 0; $moodIndex < $amount; $moodIndex++) {
            // Ensure no mood is generated twice
            do {
                $moodName = $this->faker->word();
            } while (array_key_exists(key: $moodName, array: $moods));
            $moodPercentage = $this->faker->numberBetween(1, 100);
            $moods[$moodName] = $moodPercentage;
        }

        return $moods;
    }
}
