<?php

use App\Constants\UserRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Tests\Feature\Constants\RouteName;

it(
    'test a user can successfully authenticate with right credentials',
    function () {
        $userPassword = Str::password();
        $userToBeLogged = $this->makeAdminUser($userPassword);

        // Try performing authentication
        $authenticationResponse = $this->postJson(
            uri: route(RouteName::LOGIN->value),
            data: [
                'email' => $userToBeLogged->email,
                'password' => $userPassword,
            ],
            headers: [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        );

        // Check for response details
        $authenticationResponse
            ->assertCreated()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonIsObject();
        // Check for response payload structure compliance
        expect($authenticationResponse->json())
            ->toHaveKeys([
                'name',
                'token',
                'expires',
            ])
            ->name->toBeString()
            ->token->toBeString()
            ->expires->toBeString();

        $expirationDate = Carbon::parse($authenticationResponse->json('expires'));
        expect($expirationDate)->isFuture()->toBeTrue();
    },
);

it(
    'test a user is unable to authenticate with wrong credentials',
    function () {
        $rightUserPassword = Str::password();
        $wrongUserPassword = Str::password();
        expect($rightUserPassword === $wrongUserPassword)->toBeFalse();

        $userToBeLogged = $this->makeAdminUser($rightUserPassword);

        // Try performing authentication with wrong password
        $authenticationResponse = $this->postJson(
            uri: route(RouteName::LOGIN->value),
            data: [
                'email' => $userToBeLogged->email,
                'password' => $wrongUserPassword,
            ],
            headers: [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        );

        // Check for response details
        $authenticationResponse
            ->assertUnauthorized()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonIsObject();
    },
);
