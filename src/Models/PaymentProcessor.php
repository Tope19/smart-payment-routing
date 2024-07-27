<?php

namespace Xsotechs\SmartPaymentRouting\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentProcessor extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'is_active', 'transaction_cost', 'reliability_score',
        'supported_currencies', 'supported_countries', 'config'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'transaction_cost' => 'float',
        'reliability_score' => 'float',
        'supported_currencies' => 'array',
        'supported_countries' => 'array',
        'config' => 'array',
    ];
}