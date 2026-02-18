<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $fillable = ['name', 'display_name', 'config', 'is_active', 'is_sandbox', 'sort_order'];

    protected $casts = [
        'config' => 'encrypted:array',
        'is_active' => 'boolean',
        'is_sandbox' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
