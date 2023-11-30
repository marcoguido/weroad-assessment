<?php

namespace App\OpenApi\Responses\Tours;

use App\OpenApi\Schemas\Pagination\PaginationLinksSchema;
use App\OpenApi\Schemas\Pagination\PaginationMetaSchema;
use App\OpenApi\Schemas\Tours\TourOutputSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class ListToursResponse extends ResponseFactory
{
    public function build(): Response
    {
        return Response::ok()
            ->description('Available tour list for the travel')
            ->content(
                MediaType::json()->schema(
                    Schema::object()->properties(
                        Schema::array('data')->items(TourOutputSchema::ref()),
                        PaginationLinksSchema::ref('links'),
                        PaginationMetaSchema::ref('meta'),
                    ),
                ),
            );
    }
}
