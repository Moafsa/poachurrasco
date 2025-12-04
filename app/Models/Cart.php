<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'session_id',
    ];

    /**
     * Get the user that owns the cart
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items in the cart
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get or create cart for authenticated user
     */
    public static function getOrCreateForUser(?int $userId, ?string $sessionId = null): self
    {
        if ($userId) {
            return static::firstOrCreate(
                ['user_id' => $userId, 'deleted_at' => null],
                ['user_id' => $userId]
            );
        }

        if ($sessionId) {
            return static::firstOrCreate(
                ['session_id' => $sessionId, 'deleted_at' => null],
                ['session_id' => $sessionId]
            );
        }

        throw new \Exception('Either user_id or session_id must be provided');
    }

    /**
     * Merge session cart into user cart
     */
    public static function mergeSessionCartToUser(string $sessionId, int $userId): self
    {
        $sessionCart = static::where('session_id', $sessionId)
            ->whereNull('deleted_at')
            ->first();

        $userCart = static::getOrCreateForUser($userId);

        if ($sessionCart && $sessionCart->id !== $userCart->id) {
            // Merge items from session cart to user cart
            foreach ($sessionCart->items as $sessionItem) {
                $existingItem = $userCart->items()
                    ->where('product_id', $sessionItem->product_id)
                    ->first();

                if ($existingItem) {
                    $existingItem->increment('quantity', $sessionItem->quantity);
                } else {
                    $userCart->items()->create([
                        'product_id' => $sessionItem->product_id,
                        'quantity' => $sessionItem->quantity,
                        'price' => $sessionItem->price,
                    ]);
                }
            }

            // Delete session cart
            $sessionCart->delete();
        }

        return $userCart;
    }

    /**
     * Get cart total
     */
    public function getTotalAttribute(): float
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }

    /**
     * Get cart item count
     */
    public function getItemCountAttribute(): int
    {
        return $this->items->sum('quantity');
    }
}




