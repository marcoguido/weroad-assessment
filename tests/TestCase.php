<?php

namespace Tests;

use Database\Seeders\DevelopmentSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    // Automatically invokes development seeder
    // when refreshing database
    protected string $seeder = DevelopmentSeeder::class;

    protected static string $PUBLIC_TRAVELS_API_PATH = '/api/public/travels';

    protected static string $PRIVATE_TRAVELS_API_PATH = '/api/admin/travels';

    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->afterApplicationCreatedCallbacks[] = function () {
            // Override pagination upper limit only in tests context
            // to allow pagination avoidance for some API checks
            config()->set('json-api-paginate.max_results', PHP_INT_MAX);
        };
    }
}
