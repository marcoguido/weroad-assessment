<?php

namespace Tests\Feature\Concerns;

use App\Constants\UserRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;

trait CreatesPrivilegedUsers
{
    /**
     * @param  UserRole[]  $userRoles
     */
    private function makeUserWithRoles(array $userRoles, ?string $userPassword = null): User
    {
        // Fetch the roles which will be associated to an administrator
        $administrativeRoles = Role::query()
            ->whereIn(
                column: 'name',
                values: $userRoles,
            )
            ->get();
        expect($administrativeRoles)->toHaveCount(2);

        // Create a new user to authenticate
        $userToBeLogged = User::factory()
            ->createOne([
                'name' => fake()->name,
                'email' => fake()->safeEmail,
                'password' => $userPassword ?: Str::password(),
            ]);
        $userToBeLogged
            ->roles()
            ->sync($administrativeRoles->pluck('id'));

        return $userToBeLogged->refresh();
    }
}
