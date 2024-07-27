<?php

namespace Xsotechs\SmartPaymentRouting\Services;

use Xsotechs\SmartPaymentRouting\Models\PaymentProcessor;

class PaymentProcessorService
{
    public function create(array $data): PaymentProcessor
    {
        return PaymentProcessor::create($data);
    }

    public function update(PaymentProcessor $processor, array $data): bool
    {
        return $processor->update($data);
    }

    public function delete(PaymentProcessor $processor): bool
    {
        return $processor->delete();
    }

    public function getAll()
    {
        return PaymentProcessor::all();
    }

    public function getActive()
    {
        return PaymentProcessor::where('is_active', true)->get();
    }
}