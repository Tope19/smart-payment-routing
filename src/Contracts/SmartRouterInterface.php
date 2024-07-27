<?php

namespace Xsotechs\SmartPaymentRouting\Contracts;

use Xsotechs\SmartPaymentRouting\Models\PaymentProcessor;

interface SmartRouterInterface
{
    public function route(array $paymentData): PaymentProcessor;
}