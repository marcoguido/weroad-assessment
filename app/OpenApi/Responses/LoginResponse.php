<?php

namespace App\OpenApi\Responses;

use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Illuminate\Support\Str;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class LoginResponse extends ResponseFactory implements Reusable
{
    public function build(): Response
    {
        return Response::created()
            ->description('User successfully authenticated')
            ->content(
                MediaType::json()->schema(
                    Schema::object()->properties(
                        Schema::string('name')
                            ->description('Randomly generated token name')
                            ->example(Str::random())
                            ->maxLength(16),
                        Schema::string('token')
                            ->description('The JWT token to be used as `Authorization` header in `Auth-Only` APIs'),
                        Schema::string('expires')
                            ->description('When the token will expire')
                            ->format(Schema::FORMAT_DATE_TIME),
                    )
                )
            );
    }
}
