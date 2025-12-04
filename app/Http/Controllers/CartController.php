<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Get cart items (with cache for authenticated users)
     */
    public function index(Request $request)
    {
        $cart = $this->getCart($request);
        $items = [];
        $subtotal = 0;

        // Load products with cache
        $productIds = $cart->items->pluck('product_id')->toArray();
        $products = $this->getCachedProducts($productIds);

        foreach ($cart->items as $cartItem) {
            $product = $products->get($cartItem->product_id);
            
            if (!$product || !$product->is_active) {
                continue;
            }

            $quantity = $cartItem->quantity;
            $price = $cartItem->price; // Use stored price
            $itemTotal = $price * $quantity;

            $items[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_image' => $product->images[0] ?? null,
                'establishment_id' => $product->establishment_id,
                'establishment_name' => $product->establishment->name ?? 'Unknown',
                'price' => $price,
                'quantity' => $quantity,
                'total' => $itemTotal,
            ];

            $subtotal += $itemTotal;
        }

        return response()->json([
            'success' => true,
            'items' => $items,
            'subtotal' => $subtotal,
            'count' => count($items),
        ]);
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Get product with cache
        $product = Cache::remember("product_{$request->product_id}", 3600, function () use ($request) {
            return Product::with('establishment')->findOrFail($request->product_id);
        });

        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Product is not available',
            ], 400);
        }

        // Check stock if tracking is enabled
        if ($product->track_stock && $product->stock_quantity < $request->quantity && !$product->allow_backorder) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock available',
            ], 400);
        }

        $cart = $this->getCart($request);
        $cartItem = $cart->items()->where('product_id', $request->product_id)->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            
            // Check stock again with new quantity
            if ($product->track_stock && $product->stock_quantity < $newQuantity && !$product->allow_backorder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock available',
                ], 400);
            }

            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $cart->items()->create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'price' => $product->price, // Store current price
            ]);
        }

        // Clear cart cache
        $this->clearCartCache($cart);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'cart_count' => $cart->fresh()->item_count,
        ]);
    }

    /**
     * Update item quantity in cart
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $product = Cache::remember("product_{$request->product_id}", 3600, function () use ($request) {
            return Product::findOrFail($request->product_id);
        });

        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Product is not available',
            ], 400);
        }

        // Check stock
        if ($product->track_stock && $product->stock_quantity < $request->quantity && !$product->allow_backorder) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock available',
            ], 400);
        }

        $cart = $this->getCart($request);
        $cartItem = $cart->items()->where('product_id', $request->product_id)->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in cart',
            ], 404);
        }

        $cartItem->update(['quantity' => $request->quantity]);
        $this->clearCartCache($cart);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated',
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request, $productId)
    {
        $cart = $this->getCart($request);
        $cartItem = $cart->items()->where('product_id', $productId)->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in cart',
            ], 404);
        }

        $cartItem->delete();
        $this->clearCartCache($cart);

        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart',
            'cart_count' => $cart->fresh()->item_count,
        ]);
    }

    /**
     * Clear cart
     */
    public function clear(Request $request)
    {
        $cart = $this->getCart($request);
        $cart->items()->delete();
        $this->clearCartCache($cart);

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared',
        ]);
    }

    /**
     * Get cart count
     */
    public function count(Request $request)
    {
        $cart = $this->getCart($request);
        $count = Cache::remember(
            "cart_count_{$cart->id}",
            300, // 5 minutes
            fn() => $cart->item_count
        );

        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }

    /**
     * Calculate totals with promotions (with cache)
     */
    public function calculateTotals(Request $request)
    {
        $cart = $this->getCart($request);
        $establishmentId = $request->input('establishment_id');
        $promoCode = $request->input('promo_code');
        $deliveryType = $request->input('type', 'pickup');

        // Cache key based on cart, establishment, promo code and delivery type
        $cacheKey = "cart_totals_{$cart->id}_{$establishmentId}_{$promoCode}_{$deliveryType}";

        $totals = Cache::remember($cacheKey, 300, function () use ($cart, $establishmentId, $promoCode, $deliveryType) {
            $items = [];
            $subtotal = 0;
            $establishment = null;

            $productIds = $cart->items->pluck('product_id')->toArray();
            $products = $this->getCachedProducts($productIds);

            foreach ($cart->items as $cartItem) {
                $product = $products->get($cartItem->product_id);
                
                if (!$product || !$product->is_active) {
                    continue;
                }

                // If establishment_id is provided, only include items from that establishment
                if ($establishmentId && $product->establishment_id != $establishmentId) {
                    continue;
                }

                if (!$establishment) {
                    $establishment = $product->establishment;
                }

                $quantity = $cartItem->quantity;
                $price = $cartItem->price;
                $itemTotal = $price * $quantity;

                $items[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $price,
                    'quantity' => $quantity,
                    'total' => $itemTotal,
                ];

                $subtotal += $itemTotal;
            }

            // Calculate delivery fee
            $deliveryFee = 0;
            if ($deliveryType === 'delivery' && $establishment) {
                $deliveryFee = $establishment->delivery_fee ?? 5.00;
            }

            // Apply promotion if provided
            $discount = 0;
            $promotion = null;
            if ($promoCode && $establishment) {
                $promotion = Promotion::where('establishment_id', $establishment->id)
                    ->where('promo_code', $promoCode)
                    ->where('status', 'active')
                    ->where(function($query) {
                        $query->whereNull('starts_at')
                              ->orWhere('starts_at', '<=', now());
                    })
                    ->where(function($query) {
                        $query->whereNull('ends_at')
                              ->orWhere('ends_at', '>=', now());
                    })
                    ->first();

                if ($promotion) {
                    // Check minimum order value
                    if (!$promotion->minimum_order_value || $subtotal >= $promotion->minimum_order_value) {
                        if ($promotion->promotion_type === 'percentage') {
                            $discount = ($subtotal * $promotion->discount_value) / 100;
                        } else {
                            $discount = $promotion->discount_value;
                        }
                        
                        // Don't allow discount greater than subtotal
                        $discount = min($discount, $subtotal);
                    }
                }
            }

            $total = $subtotal + $deliveryFee - $discount;

            return [
                'subtotal' => round($subtotal, 2),
                'delivery_fee' => round($deliveryFee, 2),
                'discount' => round($discount, 2),
                'total' => round($total, 2),
                'promotion' => $promotion ? [
                    'id' => $promotion->id,
                    'title' => $promotion->title,
                    'promo_code' => $promotion->promo_code,
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            ...$totals,
        ]);
    }

    /**
     * Get or create cart (database for authenticated, session for guests)
     */
    protected function getCart(Request $request): Cart
    {
        if (Auth::check()) {
            // Use database cart for authenticated users
            return Cart::getOrCreateForUser(Auth::id());
        }

        // Use session-based cart for guests
        $sessionId = $request->session()->getId();
        return Cart::getOrCreateForUser(null, $sessionId);
    }

    /**
     * Get cached products
     */
    protected function getCachedProducts(array $productIds)
    {
        return collect($productIds)->mapWithKeys(function ($productId) {
            $product = Cache::remember("product_{$productId}", 3600, function () use ($productId) {
                return Product::with('establishment')->find($productId);
            });
            return [$productId => $product];
        })->filter();
    }

    /**
     * Clear cart-related cache
     */
    protected function clearCartCache(Cart $cart): void
    {
        Cache::forget("cart_count_{$cart->id}");
        // Clear totals cache for this cart
        Cache::tags(['cart_totals'])->flush();
    }
}
