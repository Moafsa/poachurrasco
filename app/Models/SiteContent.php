<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'label',
        'content',
        'type',
        'page',
        'section',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get content by key
     */
    public static function getByKey(string $key, $default = null)
    {
        $content = static::where('key', $key)->first();
        return $content ? $content->content : $default;
    }

    /**
     * Set content by key
     */
    public static function setByKey(string $key, string $content, array $attributes = []): self
    {
        return static::updateOrCreate(
            ['key' => $key],
            array_merge([
                'content' => $content,
            ], $attributes)
        );
    }

    /**
     * Get all contents for a specific page
     */
    public static function getByPage(string $page)
    {
        return static::where('page', $page)->get()->keyBy('key');
    }
}






