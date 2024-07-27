<?php

namespace Xsotechs\SmartPaymentRouting\Tests\Unit;

use Mockery;
use Xsotechs\SmartPaymentRouting\Tests\TestCase;
use Xsotechs\SmartPaymentRouting\Models\PaymentProcessor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Xsotechs\SmartPaymentRouting\Services\PaymentProcessorService;

class PaymentProcessorServiceTest extends TestCase
{
    use RefreshDatabase;
    protected $service;
    protected $paymentProcessorMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentProcessorMock = Mockery::mock(PaymentProcessor::class);
        $this->service = new PaymentProcessorService();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCreate()
    {
        $data = [
            'name' => 'Test Processor',
            'is_active' => true,
            'transaction_cost' => 0.015,
            'reliability_score' => 0.98,
            'supported_currencies' => ['NGN', 'USD'],
            'supported_countries' => ['NG', 'US'],
            'config' => ['secret_key' => 'test_secret', 'public_key' => 'test_public'],
        ];

        // $this->paymentProcessorMock->shouldReceive('create')->once()->with($data)->andReturn(new PaymentProcessor($data));

        $result = $this->service->create($data);

        $this->assertInstanceOf(PaymentProcessor::class, $result);
        $this->assertEquals('Test Processor', $result->name);
    }

    public function testUpdate()
    {
        // Create an instance of PaymentProcessor with dummy data
        $processor = new PaymentProcessor([
            'name' => 'Test Processor',
            'is_active' => true,
            'transaction_cost' => 1.99,
            'reliability_score' => 99.9,
            'supported_currencies' => json_encode(['USD', 'NGN']),
            'supported_countries' => json_encode(['US', 'NG']),
            'config' => json_encode(['secret_key' => 'test_secret', 'public_key' => 'test_public']),
        ]);

        // Save the processor instance to the database
        $processor->save();

        // Data to be updated
        $data = ['name' => 'Updated Processor'];

        // Assume you have a service class that handles the update
        $result = $this->service->update($processor, $data);

        // Assert the update result
        $this->assertTrue($result);

        // Refresh the processor instance to get the updated data
        $processor->refresh();

        // Assert that the processor's name was updated
        $this->assertEquals('Updated Processor', $processor->name);
    }

    public function testDelete()
    {
        // Create an instance of PaymentProcessor with dummy data
        $processor = new PaymentProcessor([
            'name' => 'Test Processor',
            'is_active' => true,
            'transaction_cost' => 1.99,
            'reliability_score' => 99.9,
            'supported_currencies' => json_encode(['USD', 'NGN']),
            'supported_countries' => json_encode(['US', 'NG']),
            'config' => json_encode(['secret_key' => 'test_secret', 'public_key' => 'test_public']),
        ]);

        // Save the processor instance to the database
        $processor->save();

        // Assume you have a service class that handles the delete
        $result = $this->service->delete($processor);

        // Assert the delete result
        $this->assertTrue($result);

        // Assert that the processor was deleted
        $this->assertNull(PaymentProcessor::find($processor->id));
    }


    public function testGetAll()
    {
        // Create instances of PaymentProcessor with dummy data
        $processor1 = PaymentProcessor::create([
            'name' => 'Test Processor 1',
            'is_active' => true,
            'transaction_cost' => 1.99,
            'reliability_score' => 99.9,
            'supported_currencies' => json_encode(['USD', 'NGN']),
            'supported_countries' => json_encode(['US', 'NG']),
            'config' => json_encode(['secret_key' => 'test_secret', 'public_key' => 'test_public']),
        ]);

        $processor2 = PaymentProcessor::create([
            'name' => 'Test Processor 2',
            'is_active' => true,
            'transaction_cost' => 1.99,
            'reliability_score' => 99.9,
            'supported_currencies' => json_encode(['USD', 'NGN']),
            'supported_countries' => json_encode(['US', 'NG']),
            'config' => json_encode(['secret_key' => 'test_secret', 'public_key' => 'test_public']),
        ]);

        // Assume you have a service class that retrieves all processors
        $result = $this->service->getAll();

        // Assert the retrieval result
        $this->assertCount(2, $result);
        $this->assertInstanceOf(PaymentProcessor::class, $result[0]);
        $this->assertInstanceOf(PaymentProcessor::class, $result[1]);
    }

    public function testGetActive()
    {
        // Create instances of PaymentProcessor with dummy data
        $activeProcessor = PaymentProcessor::create([
            'name' => 'Active Processor',
            'is_active' => true,
            'transaction_cost' => 1.99,
            'reliability_score' => 99.9,
            'supported_currencies' => json_encode(['USD', 'NGN']),
            'supported_countries' => json_encode(['US', 'NG']),
            'config' => json_encode(['secret_key' => 'test_secret', 'public_key' => 'test_public']),
        ]);
    
        $inactiveProcessor = PaymentProcessor::create([
            'name' => 'Inactive Processor',
            'is_active' => false,
            'transaction_cost' => 1.99,
            'reliability_score' => 99.9,
            'supported_currencies' => json_encode(['USD', 'NGN']),
            'supported_countries' => json_encode(['US', 'NG']),
            'config' => json_encode(['secret_key' => 'test_secret', 'public_key' => 'test_public']),
        ]);
    
        // Assume you have a service class that retrieves active processors
        $result = $this->service->getActive();
    
        // Assert the retrieval result
        $this->assertCount(1, $result);
        $this->assertTrue($result[0]->is_active);
        $this->assertEquals('Active Processor', $result[0]->name);
    }
}
