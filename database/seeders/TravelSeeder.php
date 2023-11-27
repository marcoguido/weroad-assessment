<?php

namespace Database\Seeders;

use App\Constants\UserRole;
use App\Models\Role;
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
        $travelsAmount = rand(min: 5, max: 20);
        for ($travelIndex = 0 ; $travelIndex < $travelsAmount ; $travelIndex ++) {
            $toursAmount = rand(min: 1, max: 10);
            Travel::factory()
                ->has(Tour::factory()->count($toursAmount))
                ->create();
        }

        $this->command
            ->getOutput()
            ->info("Successfully created $travelsAmount new travels!");
    }
}
