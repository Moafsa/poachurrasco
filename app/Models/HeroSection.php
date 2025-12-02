<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HeroSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'page',
        'type',
        'title',
        'subtitle',
        'primary_button_text',
        'primary_button_link',
        'secondary_button_text',
        'secondary_button_link',
        'is_active',
        'display_order',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    /**
     * Get media for this hero section
     */
    public function media(): HasMany
    {
        return $this->hasMany(HeroMedia::class)->orderBy('display_order');
    }

    /**
     * Get active media
     */
    public function activeMedia(): HasMany
    {
        return $this->media()->orderBy('display_order');
    }

    /**
     * Get hero section by page
     */
    public static function getByPage(string $page): ?self
    {
        return static::where('page', $page)
            ->where('is_active', true)
            ->orderBy('display_order')
            ->first();
    }

    /**
     * Get primary image (first image media)
     */
    public function getPrimaryImageAttribute(): ?string
    {
        $image = $this->media()->where('type', 'image')->orderBy('display_order')->first();
        return $image ? $image->media_path : null;
    }

    /**
     * Get primary video (first video media)
     */
    public function getPrimaryVideoAttribute(): ?string
    {
        $video = $this->media()->where('type', 'video')->orderBy('display_order')->first();
        return $video ? $video->media_path : null;
    }
}

