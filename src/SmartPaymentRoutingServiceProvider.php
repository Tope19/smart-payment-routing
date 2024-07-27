<?php

namespace Xsotechs\SmartPaymentRouting;

use Illuminate\Support\ServiceProvider;
use Xsotechs\SmartPaymentRouting\Contracts\PaymentProcessorInterface;
use Xsotechs\SmartPaymentRouting\Contracts\SmartRouterInterface;
use Xsotechs\SmartPaymentRouting\Services\PaymentProcessorService;
use Xsotechs\SmartPaymentRouting\Services\SmartRouterService;

class SmartPaymentRoutingServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/Config/smart-payment-routing.php', 'smart-payment-routing');
        $this->app->singleton('smart-payment-routing', function ($app) {
            return new SmartRouterService();
        });

        // $this->app->bind(SmartRouterInterface::class, SmartRouterService::class);
        // $this->app->bind(PaymentProcessorInterface::class, PaymentProcessorService::class);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/Config/smart-payment-routing.php' => config_path('smart-payment-routing.php'),
        ], 'config');

        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
    }
}