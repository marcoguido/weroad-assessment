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

    protected static string $PUBLIC_TRAVEL_INDEX_API = '/api/public/travels';
}
