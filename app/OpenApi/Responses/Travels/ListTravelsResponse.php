<?php

namespace App\OpenApi\Responses\Travels;

use App\OpenApi\Schemas\Pagination\PaginationLinksSchema;
use App\OpenApi\Schemas\Pagination\PaginationMetaSchema;
use App\OpenApi\Schemas\Travels\TravelOutputSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class ListTravelsResponse extends ResponseFactory
{
    public function build(): Response
    {
        return Response::ok()
            ->description('Available travels')
            ->content(
                MediaType::json()->schema(
                    Schema::object()->properties(
                        Schema::array('data')->items(TravelOutputSchema::ref()),
                        PaginationLinksSchema::ref('links'),
                        PaginationMetaSchema::ref('meta'),
                    ),
                ),
            );
    }
}
