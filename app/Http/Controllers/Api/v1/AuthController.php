<?php

namespace App\Http\Controllers\Api\v1;

use App\Actions\User\FindUserByEmail;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\LoginRequest;
use App\OpenApi\RequestBodies\DoLoginRequestBody;
use App\OpenApi\Responses\LoginResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class AuthController extends Controller
{
    /**
     * Login entrypoint to get a new JWT for further API calls
     *
     * @throws AuthorizationException
     */
    #[OpenApi\Operation(
        tags: [
            'Auth',
        ],
        method: 'POST',
    )]
    #[OpenApi\RequestBody(factory: DoLoginRequestBody::class)]
    #[OpenApi\Response(factory: LoginResponse::class)]
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
