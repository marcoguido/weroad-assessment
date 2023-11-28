<?php

namespace App\Http\Controllers\Api\v1;

use App\Actions\User\FindUserByEmail;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\LoginRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __invoke(FindUserByEmail $userFinder, LoginRequest $request): JsonResponse
    {
        if (! Auth::attempt($request->getCredentials())) {
            throw new AuthorizationException("Either username or password are invalid");
        }

        $apiToken = $userFinder->execute(
            email: $request->getEmail(),
            failIfNotFound: true,
        )->createToken(Str::random());

        return new JsonResponse(
            data: [
                'name' => $apiToken->accessToken->name,
                'token' => $apiToken->plainTextToken,
                'expires' => $apiToken->accessToken->expires_at
            ],
            status: JsonResponse::HTTP_CREATED,
        );
    }
}
