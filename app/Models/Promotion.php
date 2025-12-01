<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promotion extends Model
{
    use HasFactory;

    public const TYPES = [
        'percentage',
        'fixed',
    ];

    public const STATUSES = [
        'draft',
        'scheduled',
        'active',
        'paused',
        'expired',
    ];

    protected $fillable = [
        'establishment_id',
        'title',
        'slug',
        'description',
        'promotion_type',
        'discount_value',
        'minimum_order_value',
        'promo_code',
        'usage_limit',
        'usage_count',
        'status',
        'starts_at',
        'ends_at',
        'applicable_products',
        'channels',
        'is_stackable',
        'is_featured',
        'banner_image',
        'terms',
        'metadata',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'minimum_order_value' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_count' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'applicable_products' => 'array',
        'channels' => 'array',
        'is_stackable' => 'boolean',
        'is_featured' => 'boolean',
        'metadata' => 'array',
    ];

    public function establishment(): BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function markAsUsed(): void
    {
        $this->increment('usage_count');

        if ($this->usage_limit !== null && $this->usage_count >= $this->usage_limit) {
            $this->update(['status' => 'expired']);
        }
    }
}
