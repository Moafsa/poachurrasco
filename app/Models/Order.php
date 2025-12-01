<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'establishment_id',
        'order_number',
        'status',
        'type',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'delivery_address',
        'items',
        'subtotal',
        'delivery_fee',
        'discount',
        'total',
        'payment_method',
        'payment_status',
        'payment_reference',
        'confirmed_at',
        'preparing_at',
        'ready_at',
        'delivered_at',
        'cancelled_at',
        'cancellation_reason',
        'customer_notes',
        'internal_notes',
    ];

    protected $casts = [
        'items' => 'array',
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'preparing_at' => 'datetime',
        'ready_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Generate unique order number
     */
    public static function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        } while (static::where('order_number', $number)->exists());

        return $number;
    }

    /**
     * Get the user that placed the order
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the establishment for this order
     */
    public function establishment(): BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }

    /**
     * Scope for pending orders
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for active orders
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready']);
    }

    /**
     * Scope for completed orders
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'delivered');
    }

    /**
     * Scope for cancelled orders
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    /**
     * Update order status with timestamp
     */
    public function updateStatus(string $status, ?string $reason = null): bool
    {
        $updates = ['status' => $status];

        switch ($status) {
            case 'confirmed':
                $updates['confirmed_at'] = now();
                break;
            case 'preparing':
                $updates['preparing_at'] = now();
                break;
            case 'ready':
                $updates['ready_at'] = now();
                break;
            case 'delivered':
                $updates['delivered_at'] = now();
                break;
            case 'cancelled':
                $updates['cancelled_at'] = now();
                if ($reason) {
                    $updates['cancellation_reason'] = $reason;
                }
                break;
        }

        return $this->update($updates);
    }

    /**
     * Get formatted total
     */
    public function getFormattedTotalAttribute(): string
    {
        return 'R$ ' . number_format($this->total, 2, ',', '.');
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'confirmed' => 'blue',
            'preparing' => 'orange',
            'ready' => 'green',
            'delivered' => 'gray',
            'cancelled' => 'red',
            default => 'gray',
        };
    }
}


