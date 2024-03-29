<?php

namespace App\Http\Controllers\Api\v1;

use App\Actions\User\FindUserByEmail;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\LoginRequest;
use App\OpenApi\RequestBodies\DoLoginRequestBody;
use App\OpenApi\Responses\LoginResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class AuthController extends Controller
{
    /**
     * Login entrypoint to get a new JWT for further API calls
     *
     * @throws AuthenticationException
     */
    #[OpenApi\Operation(
        tags: ['Auth'],
        method: 'POST',
    )]
    #[OpenApi\RequestBody(factory: DoLoginRequestBody::class)]
    #[OpenApi\Response(
        factory: LoginResponse::class,
        statusCode: JsonResponse::HTTP_CREATED,
    )]
    public function __invoke(FindUserByEmail $userFinder, LoginRequest $request): JsonResponse
    {
        if (! Auth::attempt($request->getCredentials())) {
            throw new AuthenticationException('Either username or password are invalid');
        }

        $apiToken = $userFinder->execute(
            email: $request->getEmail(),
            failIfNotFound: true,
        )
            ->createToken(
                name: Str::random(),
                expiresAt: Carbon::now()->addMinutes(
                    config('sanctum.expiration'),
                ),
            );

        return new JsonResponse(
            data: [
                'name' => $apiToken->accessToken->name,
                'token' => $apiToken->plainTextToken,
                'expires' => $apiToken->accessToken->expires_at,
            ],
            status: JsonResponse::HTTP_CREATED,
        );
    }
}
