<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;

readonly class FindUserByEmail
{
    /**
     * @var Builder<User>
     */
    private Builder $userQuery;

    public function __construct()
    {
        $this->userQuery = User::query();
    }

    public function execute(string $email, bool $failIfNotFound = false): ?User
    {
        $fetchQuery = $this->userQuery
            ->where('email', '=', $email);

        return $failIfNotFound
            ? $fetchQuery->firstOrFail()
            : $fetchQuery->first();
    }
}
