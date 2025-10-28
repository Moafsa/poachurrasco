<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'establishment_id',
    ];

    /**
     * Get the user that favorited the establishment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the favorited establishment
     */
    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }
}