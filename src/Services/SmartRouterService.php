<?php

namespace Xsotechs\SmartPaymentRouting\Services;

use Xsotechs\SmartPaymentRouting\Contracts\SmartRouterInterface;
use Xsotechs\SmartPaymentRouting\Models\PaymentProcessor;
use Xsotechs\SmartPaymentRouting\Exceptions\PaymentRoutingException;
use Illuminate\Support\Facades\Log;

class SmartRouterService implements SmartRouterInterface
{
    public function route(array $paymentData): PaymentProcessor
    {
        try {
            $amount = $paymentData['amount'];
            $currency = $paymentData['currency'];
            $country = $paymentData['country'];

            $availableProcessors = $this->getAvailableProcessors($currency, $country);
            // dd($availableProcessors);

            if ($availableProcessors->isEmpty()) {
                throw new PaymentRoutingException("No suitable payment processor found.");
            }

            return $this->selectBestProcessor($availableProcessors, $amount);
        } catch (\Exception $e) {
            Log::error('Payment routing error: ' . $e->getMessage());
            throw new PaymentRoutingException("Error during payment routing: " . $e->getMessage());
        }
    }

    protected function getAvailableProcessors(string $currency, string $country): \Illuminate\Database\Eloquent\Collection
    {
        \Log::info("Searching for currency: $currency, country: $country");
        return PaymentProcessor::where('is_active', true)
            ->where(function ($query) use ($currency, $country) {
                $query->whereRaw('JSON_CONTAINS(supported_currencies, ?)', [json_encode($currency)])
                    ->orWhereRaw('JSON_CONTAINS(supported_countries, ?)', [json_encode($country)]);
            })
            ->get();

    }

    protected function selectBestProcessor($processors, float $amount): PaymentProcessor
    {
        return $processors->sortBy(function ($processor) use ($amount) {
            $cost = $processor->transaction_cost * $amount;
            // Normalize the reliability score to be between 0 and 1
            $normalizedReliability = $processor->reliability_score / 100;
            // Calculate a weighted score that factors in both cost and reliability
            $weightedScore = $cost * (1 - $normalizedReliability);
            return $weightedScore;
        })->first();
    }
}


