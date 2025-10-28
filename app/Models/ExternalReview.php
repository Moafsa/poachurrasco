<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ExternalReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'establishment_id',
        'external_id',
        'external_source',
        'author_name',
        'author_url',
        'profile_photo_url',
        'rating',
        'text',
        'time',
        'language',
        'original_data',
        'is_verified',
    ];

    protected $casts = [
        'time' => 'datetime',
        'original_data' => 'array',
        'is_verified' => 'boolean',
    ];

    /**
     * Get the establishment that this review belongs to
     */
    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    /**
     * Scope for verified reviews
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope for recent reviews
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('time', '>=', now()->subDays($days));
    }

    /**
     * Scope for reviews from specific source
     */
    public function scopeFromSource($query, $source)
    {
        return $query->where('external_source', $source);
    }

    /**
     * Get formatted time ago
     */
    public function getTimeAgoAttribute()
    {
        return $this->time->diffForHumans();
    }

    /**
     * Get author initials for avatar
     */
    public function getAuthorInitialsAttribute()
    {
        $names = explode(' ', $this->author_name);
        $initials = '';
        
        foreach ($names as $name) {
            if (!empty($name)) {
                $initials .= strtoupper(substr($name, 0, 1));
            }
        }
        
        return substr($initials, 0, 2);
    }

    /**
     * Get star rating display
     */
    public function getStarRatingAttribute()
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '★';
            } else {
                $stars .= '☆';
            }
        }
        return $stars;
    }
}
