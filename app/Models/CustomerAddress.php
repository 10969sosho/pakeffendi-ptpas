<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerAddress extends Model
{
    protected $fillable = [
        'customer_id',
        'label',
        'recipient_name',
        'phone',
        'address',
        'province_code',
        'province_name',
        'regency_code',
        'regency_name',
        'district_code',
        'district_name',
        'village_code',
        'village_name',
        'province',
        'city',
        'postal_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            trim((string) $this->address),
            trim((string) ($this->village_name ?: $this->village_code)),
            trim((string) ($this->district_name ?: $this->district_code)),
            trim((string) ($this->regency_name ?: $this->city)),
            trim((string) ($this->province_name ?: $this->province)),
            trim((string) $this->postal_code),
        ], fn ($v) => $v !== '');

        return implode(', ', $parts);
    }
}
