<?php

namespace Database\Seeders;

use App\Constants\UserRole;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (UserRole::values() as $role) {
            $roleAttributes = [
                'name' => $role,
            ];

            // Ensure main role exists
            Role::query()->firstOrCreate(
                attributes: $roleAttributes,
                values: $roleAttributes,
            );
        }
    }
}
