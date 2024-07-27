<?php

namespace Xsotechs\SmartPaymentRouting\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Xsotechs\SmartPaymentRouting\SmartPaymentRoutingServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            SmartPaymentRoutingServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'SmartPaymentRouting' => \Xsotechs\SmartPaymentRouting\Facades\SmartPaymentRouting::class,
        ];
    }

}
