<?php

namespace App\OpenApi\RequestBodies;

use App\OpenApi\Schemas\LoginSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use Vyuldashev\LaravelOpenApi\Factories\RequestBodyFactory;

class DoLoginRequestBody extends RequestBodyFactory
{
    public function build(): RequestBody
    {
        return RequestBody::create()
            ->description("Login request payload")
            ->required()
            ->content(
                MediaType::json()->schema(LoginSchema::ref())
            );
    }
}
