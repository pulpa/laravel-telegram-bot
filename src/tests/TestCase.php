<?php

namespace Pulpa\LaravelTelegramBot\Testing;

use Pulpa\LaravelTelegramBot\ServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }
}