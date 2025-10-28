<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BbqGuide extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'meat_type',
        'cut_type',
        'seasoning',
        'preparation_method',
        'equipment_needed',
        'cooking_time',
        'temperature_guide',
        'calories_per_100g',
        'tips',
        'serving_suggestions',
        'difficulty_level',
        'servings',
        'ingredients',
        'steps',
        'image_url',
        'is_featured',
    ];

    protected $casts = [
        'ingredients' => 'array',
        'steps' => 'array',
        'is_featured' => 'boolean',
    ];

    /**
     * Get the difficulty level badge color
     */
    public function getDifficultyColorAttribute(): string
    {
        return match($this->difficulty_level) {
            'easy' => 'green',
            'medium' => 'yellow',
            'hard' => 'red',
            default => 'gray'
        };
    }

    /**
     * Get the difficulty level in Portuguese
     */
    public function getDifficultyLabelAttribute(): string
    {
        return match($this->difficulty_level) {
            'easy' => 'Fácil',
            'medium' => 'Médio',
            'hard' => 'Difícil',
            default => 'Não definido'
        };
    }

    /**
     * Scope for featured guides
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for meat type
     */
    public function scopeByMeatType($query, $meatType)
    {
        return $query->where('meat_type', $meatType);
    }

    /**
     * Scope for difficulty level
     */
    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty_level', $difficulty);
    }
}
