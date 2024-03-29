<?php

namespace Database\Seeders;

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Database\Seeder;

/**
 * @extends Seeder<Travel>
 */
class TravelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $travelsAmount = rand(min: 20, max: 100);
        for ($travelIndex = 0; $travelIndex < $travelsAmount; $travelIndex++) {
            $travel = Travel::factory()->create();
            Tour::factory(rand(min: 5, max: 20))
                ->state([
                    'travelId' => $travel->id,
                ])
                ->ofDays($travel->numberOfDays)
                ->createMany();
        }

        $this->command
            ->getOutput()
            ->info("Successfully created $travelsAmount new travels!");
    }
}
