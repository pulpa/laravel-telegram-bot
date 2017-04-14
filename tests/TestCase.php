<?php

namespace Pulpa\Telegram\Bot\Testing;

use Pulpa\Telegram\Bot\Providers\ServiceProvider;
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
