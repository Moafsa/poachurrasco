<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'establishment_id',
        'name',
        'description',
        'category',
        'subcategory',
        'price',
        'compare_price',
        'cost_price',
        'sku',
        'barcode',
        'weight',
        'dimensions',
        'brand',
        'origin',
        'ingredients',
        'nutritional_info',
        'allergens',
        'storage_instructions',
        'expiry_date',
        'is_digital',
        'is_service',
        'is_featured',
        'is_active',
        'stock_quantity',
        'low_stock_threshold',
        'track_stock',
        'allow_backorder',
        'images',
        'videos',
        'tags',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'seo_score',
        'view_count',
        'purchase_count',
        'rating',
        'review_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'weight' => 'decimal:3',
        'dimensions' => 'array',
        'ingredients' => 'array',
        'nutritional_info' => 'array',
        'allergens' => 'array',
        'images' => 'array',
        'videos' => 'array',
        'tags' => 'array',
        'is_digital' => 'boolean',
        'is_service' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'track_stock' => 'boolean',
        'allow_backorder' => 'boolean',
        'expiry_date' => 'date',
        'rating' => 'decimal:2',
    ];

    // Relationships
    public function establishment(): BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where(function($q) {
            $q->where('track_stock', false)
              ->orWhere('stock_quantity', '>', 0)
              ->orWhere('allow_backorder', true);
        });
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByPriceRange($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%")
              ->orWhere('tags', 'like', "%{$term}%");
        });
    }

    // Accessors & Mutators
    public function getFormattedPriceAttribute()
    {
        return 'R$ ' . number_format($this->price, 2, ',', '.');
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->compare_price && $this->compare_price > $this->price) {
            return round((($this->compare_price - $this->price) / $this->compare_price) * 100);
        }
        return 0;
    }

    public function getIsOnSaleAttribute()
    {
        return $this->compare_price && $this->compare_price > $this->price;
    }

    public function getIsLowStockAttribute()
    {
        return $this->track_stock && $this->stock_quantity <= $this->low_stock_threshold;
    }

    public function getIsOutOfStockAttribute()
    {
        return $this->track_stock && $this->stock_quantity <= 0 && !$this->allow_backorder;
    }

    public function getMainImageAttribute()
    {
        return $this->images ? $this->images[0] : null;
    }

    // Methods
    public function reduceStock($quantity = 1)
    {
        if ($this->track_stock) {
            $this->decrement('stock_quantity', $quantity);
        }
    }

    public function increaseStock($quantity = 1)
    {
        if ($this->track_stock) {
            $this->increment('stock_quantity', $quantity);
        }
    }

    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function incrementPurchaseCount($quantity = 1)
    {
        $this->increment('purchase_count', $quantity);
    }
}