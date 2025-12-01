<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'bbq_full',
        'bbq_premium',
        'bbq_express',
        'bbq_events',
        'bbq_delivery',
        'bbq_class',
        'bbq_consulting',
        'grill_installation',
        'equipment_maintenance',
        'custom',
    ];

    protected $fillable = [
        'establishment_id',
        'name',
        'slug',
        'description',
        'category',
        'duration_minutes',
        'capacity',
        'price',
        'setup_fee',
        'includes_meat',
        'includes_staff',
        'includes_equipment',
        'is_featured',
        'is_active',
        'images',
        'tags',
        'addons',
        'service_hours',
        'metadata',
        'rating',
        'review_count',
        'view_count',
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'capacity' => 'integer',
        'price' => 'decimal:2',
        'setup_fee' => 'decimal:2',
        'includes_meat' => 'boolean',
        'includes_staff' => 'boolean',
        'includes_equipment' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'images' => 'array',
        'tags' => 'array',
        'addons' => 'array',
        'service_hours' => 'array',
        'metadata' => 'array',
        'rating' => 'decimal:2',
        'review_count' => 'integer',
        'view_count' => 'integer',
    ];

    public function establishment(): BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
