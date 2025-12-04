<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeroMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_section_id',
        'type',
        'media_path',
        'mime_type',
        'file_size',
        'display_order',
        'alt_text',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get hero section
     */
    public function heroSection(): BelongsTo
    {
        return $this->belongsTo(HeroSection::class);
    }

    /**
     * Get full URL for media
     */
    public function getUrlAttribute(): string
    {
        if (str_starts_with($this->media_path, 'http')) {
            return $this->media_path;
        }
        return asset('storage/' . $this->media_path);
    }
}






