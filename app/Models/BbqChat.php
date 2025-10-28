<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BbqChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'message',
        'response',
        'context',
        'is_ai_message',
    ];

    protected $casts = [
        'is_ai_message' => 'boolean',
        'context' => 'array',
    ];

    /**
     * Get the user that owns the chat
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for session messages
     */
    public function scopeBySession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Scope for user messages
     */
    public function scopeUserMessages($query)
    {
        return $query->where('is_ai_message', false);
    }

    /**
     * Scope for AI messages
     */
    public function scopeAiMessages($query)
    {
        return $query->where('is_ai_message', true);
    }
}
