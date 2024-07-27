<?php

namespace JohnDoe\BlogPackage\Tests;

use LaravelGuru\LaravelFilehandler\LaravelFilehandlerServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelFilehandlerServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}

// "scripts": {
//     "test": "vendor/bin/phpunit",
//     "test-coverage": "vendor/bin/phpunit --coverage-html coverage"
//   },