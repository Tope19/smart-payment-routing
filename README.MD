# Smart Routing Package

## Introduction

The Smart Routing Package for Laravel provides an intelligent routing system for payment gateways. It dynamically selects the most suitable payment processor based on various factors such as transaction cost, reliability, and currency support. This package ensures efficient, flexible, and secure handling of payment transactions.

## Features

- **Dynamic Routing Logic**: Intelligently routes transactions to the best payment processor.
- **Processor Management**: Manage multiple payment processors with ease.
- **Configuration Options**: Customize routing rules based on transaction cost, reliability, and other criteria.
- **Laravel Compatibility**: Compatible with the latest stable version of Laravel.


## Table of Contents

1. [Installation](#installation)
2. [Usage](#usage)
3. [Running Tests](#running-tests)

## Installation

You can install the package via composer:

```bash
composer require xsotechs/smart-payment-routing
```

After installation, publish the configuration file:

```bash
php artisan vendor:publish --provider="Xsotechs\SmartPaymentRouting\SmartPaymentRoutingServiceProvider" --tag="config"
```

## Usage
To use the smart payment routing in your Laravel application:

```php
use Xsotechs\SmartPaymentRouting\Services\SmartRouterService;


public function processPayment(Request $request)
{
    $smartRouterService = new SmartRouterService();
    $paymentData = [
        'amount' => $request->amount,
        'currency' => $request->currency,
        'country' => $request->country,
        // Add any other necessary data
    ];

    try {
        $processor = $smartRouterService->route($paymentData);
        $data = $processor->process($paymentData);
        // Use the data to initiate a payment logic
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
    }
}
```

## Managing Payment Processors
You can use the '**PaymentProcessorService**' to manage payment processors:

```php
use Xsotechs\SmartPaymentRouting\Services\PaymentProcessorService;

$processorService = new PaymentProcessorService();

// Create a new processor
$newProcessor = $processorService->create([
    'name' => 'paystack',
    'is_active' => true,
    'transaction_cost' => 0.029,
    'reliability_score' => 0.99, 
    'supported_currencies' => ['NGN', 'USD'], // Supported currencies
    'supported_countries' => ['NG', 'US'], // Supported countries
    'config' => ['api_key' => 'your_stripe_api_key'],
]);

// Update a processor
$processor = PaymentProcessor::find(1);
$processorService->update($processor, [
    'transaction_cost' => 0.028,
    'reliability_score' => 0.995,
]);

// Delete a processor
$processor = PaymentProcessor::find(1);
$processorService->delete($processor);

// Get all processors
$allProcessors = $processorService->getAll();

// Get active processors
$activeProcessors = $processorService->getActive();
```

## Running Tests

Please make sure to update the database credentials in phpunit.xml with your own when running tests for the package.
```php
<env name="DB_CONNECTION" value="mysql"/>
<env name="DB_HOST" value="127.0.0.1"/>
<env name="DB_PORT" value="8889"/>
<env name="DB_DATABASE" value="test_database"/>
<env name="DB_USERNAME" value="root"/>
<env name="DB_PASSWORD" value="root"/>
<env name="DB_SOCKET" value="/Applications/MAMP/tmp/mysql/mysql.sock"/>
```

Then run the below commands:
1. vendor/bin/phpunit tests/unit/PaymentProcessorServiceTest.php
2. vendor/bin/phpunit tests/unit/SmartRouterServiceTest.php
3. vendor/bin/phpunit tests/unit/*.php
