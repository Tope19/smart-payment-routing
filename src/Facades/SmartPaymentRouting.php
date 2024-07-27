<?php

namespace Xsotechs\SmartPaymentRouting\Facades;

use Illuminate\Support\Facades\Facade;

class SmartPaymentRouting extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'smart-payment-routing';
    }
}