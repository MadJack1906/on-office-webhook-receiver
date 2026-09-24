<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryTarget extends Model
{
    protected $fillable = [
        'webhook_endpoint_id',
        'name',
        'url',
        'headers',
        'secret',
        'timeout',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'headers' => 'array',
            'is_active' => 'boolean',
            'timeout' => 'integer',
        ];
    }

    public function endpoint(): BelongsTo
    {
        return $this->belongsTo(WebhookEndpoint::class, 'webhook_endpoint_id');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(DeliveryAttempt::class);
    }
}
