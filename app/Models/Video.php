<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'tutorial',
        'recipe',
        'event',
        'behind_the_scenes',
        'interview',
        'other',
    ];

    protected $fillable = [
        'establishment_id',
        'title',
        'slug',
        'description',
        'category',
        'video_url',
        'provider',
        'provider_video_id',
        'duration_seconds',
        'thumbnail_url',
        'tags',
        'is_featured',
        'is_published',
        'published_at',
        'view_count',
        'like_count',
        'share_count',
        'captions',
        'metadata',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'tags' => 'array',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'view_count' => 'integer',
        'like_count' => 'integer',
        'share_count' => 'integer',
        'captions' => 'array',
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
