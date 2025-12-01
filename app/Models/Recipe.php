<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recipe extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'beef',
        'pork',
        'poultry',
        'seafood',
        'sides',
        'desserts',
        'drinks',
        'other',
    ];

    public const DIFFICULTIES = [
        'easy',
        'medium',
        'hard',
    ];

    protected $fillable = [
        'establishment_id',
        'title',
        'slug',
        'summary',
        'description',
        'category',
        'difficulty',
        'prep_time_minutes',
        'cook_time_minutes',
        'rest_time_minutes',
        'servings',
        'ingredients',
        'instructions',
        'tips',
        'images',
        'video_url',
        'is_featured',
        'is_published',
        'published_at',
        'view_count',
        'favorite_count',
        'nutrition_facts',
        'metadata',
    ];

    protected $casts = [
        'prep_time_minutes' => 'integer',
        'cook_time_minutes' => 'integer',
        'rest_time_minutes' => 'integer',
        'servings' => 'integer',
        'ingredients' => 'array',
        'instructions' => 'array',
        'tips' => 'array',
        'images' => 'array',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'view_count' => 'integer',
        'favorite_count' => 'integer',
        'nutrition_facts' => 'array',
        'metadata' => 'array',
    ];

    public function establishment(): BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
