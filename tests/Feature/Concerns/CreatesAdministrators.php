<?php

namespace Tests\Feature\Concerns;

use App\Constants\UserRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;

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
