<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryAttempt extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'received_webhook_id',
        'delivery_target_id',
        'attempt',
        'status',
        'response_status',
        'response_body',
        'error',
        'duration_ms',
        'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'delivered_at' => 'datetime',
        ];
    }

    public function webhook(): BelongsTo
    {
        return $this->belongsTo(ReceivedWebhook::class, 'received_webhook_id');
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(DeliveryTarget::class, 'delivery_target_id');
    }
}
