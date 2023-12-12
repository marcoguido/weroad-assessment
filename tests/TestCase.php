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
}
