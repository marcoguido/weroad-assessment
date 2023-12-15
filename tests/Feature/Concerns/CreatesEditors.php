<?php

namespace Tests\Feature\Concerns;

use App\Constants\UserRole;
use App\Models\User;

trait CreatesEditors
{
    use CreatesPrivilegedUsers;

    private function makeEditorUser(?string $userPassword = null): User
    {
        return $this->makeUserWithRoles(
            userRoles: [
                UserRole::EDITOR,
            ],
            userPassword: $userPassword,
        );
    }
}
