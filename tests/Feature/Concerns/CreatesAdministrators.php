<?php

namespace Tests\Feature\Concerns;

use App\Constants\UserRole;
use App\Models\User;

trait CreatesAdministrators
{
    use CreatesPrivilegedUsers;

    private function makeAdminUser(?string $userPassword = null): User
    {
        return $this->makeUserWithRoles(
            userRoles: [
                UserRole::ADMIN,
                UserRole::EDITOR,
            ],
            userPassword: $userPassword,
        );
    }
}
