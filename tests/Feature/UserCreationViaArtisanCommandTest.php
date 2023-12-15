<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use function Pest\Laravel\artisan;

it(
    'test a new administrator user can successfully be created with CLI-provided parameters',
    function () {
        $userName = 'John Doe';
        $userEmailAddress = 'j.doe@test.xyz';
        $userPassword = 'Password123';

        artisan(
            command: 'make:admin-user',
            parameters: [
                'name' => $userName,
                'email' => $userEmailAddress,
                'password' => $userPassword,
            ],
        )
            ->expectsTable(
                headers: [
                    'Name',
                    'Email',
                    'Password',
                ],
                rows: [
                    [
                        $userName,
                        $userEmailAddress,
                        $userPassword,
                    ],
                ],
                tableStyle: 'symfony-style-guide',
            )
            ->assertExitCode(0)
            ->assertSuccessful();

        /** @var User $newUser */
        $newUser = User::query()
            ->with('roles')
            ->firstWhere('email', '=', $userEmailAddress);

        expect($newUser)
            ->name->toBeString($userName)
            ->email->toBeString($userEmailAddress)
            ->and(Hash::check(value: $userPassword, hashedValue: $newUser->password))->toBeTrue()
            ->and($newUser->isAdmin())->toBeTrue()
            ->and($newUser->isEditor())->toBeTrue();
    },
);
