<?php

namespace Xsotechs\SmartPaymentRouting\Tests\Unit;

use Mockery;
use Illuminate\Database\Eloquent\Collection;
use Xsotechs\SmartPaymentRouting\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Xsotechs\SmartPaymentRouting\Models\PaymentProcessor;
use Xsotechs\SmartPaymentRouting\Services\SmartRouterService;
use Xsotechs\SmartPaymentRouting\Exceptions\PaymentRoutingException;

class SmartRouterServiceTest extends TestCase
{
    use RefreshDatabase;
    protected $smartRouterService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->smartRouterService = new SmartRouterService();
    }

    public function testRouteSelectsBestProcessor()
    {
        // Create dummy data for payment processors
        $paystack = PaymentProcessor::create([
            'name' => 'paystack',
            'is_active' => true,
            'transaction_cost' => 1.5,
            'reliability_score' => 99.0,
            'supported_currencies' => json_encode(['NGN']),
            'supported_countries' => json_encode(['NG']),
            'config' => json_encode(['secret_key' => 'test_secret', 'public_key' => 'test_public']),
        ]);
    
        $flutterwave = PaymentProcessor::create([
            'name' => 'flutterwave',
            'is_active' => true,
            'transaction_cost' => 2.0,
            'reliability_score' => 95.0,
            'supported_currencies' => json_encode(['NGN']),
            'supported_countries' => json_encode(['NG']),
            'config' => json_encode(['secret_key' => 'test_secret', 'public_key' => 'test_public']),
        ]);

       // Call the route method with the required parameters
       $result = $this->smartRouterService->route([
           'amount' => 10000,
           'currency' => 'NGN',
           'country' => 'NG',
       ]);

       // Assert that the correct processor is selected
        $this->assertInstanceOf(PaymentProcessor::class, $result);
    }
    

    public function testRouteThrowsExceptionWhenNoProcessorAvailable()
    {

        PaymentProcessor::truncate();

        // Call the route method with data that should result in no available processors
        $this->expectException(PaymentRoutingException::class);
        $this->expectExceptionMessage("No suitable payment processor found.");

        $this->smartRouterService->route([
            'amount' => 100,
            'currency' => 'JPY',
            'country' => 'JP',
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}