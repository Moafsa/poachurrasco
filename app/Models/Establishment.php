<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Establishment extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        try {
            static::creating(function (self $establishment): void {
                if (empty($establishment->slug)) {
                    $establishment->slug = static::generateUniqueSlug($establishment->name);
                }
            });

            static::updating(function (self $establishment): void {
                if ($establishment->isDirty('name')) {
                    $establishment->slug = static::generateUniqueSlug($establishment->name, $establishment->id);
                }
            });
        } catch (\Exception $e) {
            \Log::error('Error in Establishment booted method: ' . $e->getMessage());
        }
    }

    /**
     * Generate a unique slug for the establishment using the provided name.
     */
    protected static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);

        if ($baseSlug === '') {
            $baseSlug = 'establishment';
        }

        $slug = $baseSlug;
        $suffix = 1;

        while (
            static::query()
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'address',
        'city',
        'state',
        'zip_code',
        'phone',
        'email',
        'website',
        'latitude',
        'longitude',
        'category',
        'status',
        'is_featured',
        'rating',
        'review_count',
        'external_rating',
        'external_review_count',
        'opening_hours',
        'payment_methods',
        'amenities',
        'images',
        'logo',
        'cover_image',
        'social_media',
        'subscription_plan',
        'subscription_status',
        'subscription_expires_at',
        'commission_rate',
        'delivery_fee',
        'is_verified',
        'verification_date',
        'has_tourism_quality_seal',
        'tourism_quality_seal_date',
        'tourism_quality_seal_reason',
        'tourism_quality_seal_notes',
        'meta_title',
        'meta_description',
        'meta_keywords',
        // External API fields
        'external_id',
        'external_source',
        'external_data',
        'last_synced_at',
        'is_external',
        'is_merged',
        'place_id',
        'business_status',
        'types',
        'price_level',
        'photos',
        'reviews_external',
        'vicinity',
        'formatted_address',
        'formatted_phone_number',
        'international_phone_number',
        'opening_hours_external',
        'permanently_closed',
        'user_ratings_total',
        'photo_urls',
    ];

    protected $casts = [
        'opening_hours' => 'array',
        'payment_methods' => 'array',
        'amenities' => 'array',
        'images' => 'array',
        'social_media' => 'array',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
        'has_tourism_quality_seal' => 'boolean',
        'subscription_expires_at' => 'datetime',
        'verification_date' => 'datetime',
        'tourism_quality_seal_date' => 'datetime',
        'rating' => 'decimal:2',
        'external_rating' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        // External API casts
        'external_data' => 'array',
        'last_synced_at' => 'datetime',
        'is_external' => 'boolean',
        'is_merged' => 'boolean',
        'types' => 'array',
        'photos' => 'array',
        'photo_urls' => 'array',
        'reviews_external' => 'array',
        'opening_hours_external' => 'array',
        'permanently_closed' => 'boolean',
        'price_level' => 'decimal:1',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function externalReviews(): HasMany
    {
        return $this->hasMany(ExternalReview::class);
    }

    /**
     * Route key for implicit model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function allReviews()
    {
        return $this->reviews()->union($this->externalReviews());
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeWithTourismQualitySeal($query)
    {
        return $query->where('has_tourism_quality_seal', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeExternal($query)
    {
        return $query->where('is_external', true);
    }

    public function scopeUserCreated($query)
    {
        return $query->where('is_external', false);
    }

    public function scopeMerged($query)
    {
        return $query->where('is_merged', true);
    }

    public function scopeNeedsSync($query, $hours = 24)
    {
        return $query->where(function($q) use ($hours) {
            $q->whereNull('last_synced_at')
              ->orWhere('last_synced_at', '<', now()->subHours($hours));
        });
    }

    public function scopeByExternalSource($query, $source)
    {
        return $query->where('external_source', $source);
    }

    public function scopeNearby($query, $latitude, $longitude, $radius = 10)
    {
        return $query->selectRaw("*, 
            (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance",
            [$latitude, $longitude, $latitude])
            ->having('distance', '<', $radius)
            ->orderBy('distance');
    }

    // Accessors & Mutators
    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->city} - {$this->state}, {$this->zip_code}";
    }

    public function getAverageRatingAttribute()
    {
        return $this->rating ?? 0;
    }

    public function getIsPremiumAttribute()
    {
        return $this->subscription_plan === 'premium' && 
               $this->subscription_status === 'active' && 
               $this->subscription_expires_at > now();
    }

    // Methods
    public function updateRating()
    {
        // This would typically calculate from reviews
        // For now, we'll use the stored rating
        return $this->rating;
    }

    public function getDistanceFrom($latitude, $longitude)
    {
        if (!$this->latitude || !$this->longitude) {
            return null;
        }

        $earthRadius = 6371; // km

        $latDiff = deg2rad($latitude - $this->latitude);
        $lonDiff = deg2rad($longitude - $this->longitude);

        $a = sin($latDiff/2) * sin($latDiff/2) +
             cos(deg2rad($this->latitude)) * cos(deg2rad($latitude)) *
             sin($lonDiff/2) * sin($lonDiff/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }
}