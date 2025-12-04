<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Establishment;
use App\Services\NotificationService;
use App\Jobs\ProcessOrderJob;
use App\Jobs\SendOrderConfirmationJob;
use App\Jobs\UpdateInventoryJob;
use App\Jobs\NotifyEstablishmentJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['establishment', 'user'])
            ->where('user_id', Auth::id());

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by establishment
        if ($request->has('establishment_id')) {
            $query->where('establishment_id', $request->establishment_id);
        }

        $orders = $query->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'orders' => $orders,
        ]);
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'establishment_id' => 'required|exists:establishments,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'nullable|string',
            'delivery_address' => 'nullable|string|required_if:type,delivery',
            'type' => 'required|in:pickup,delivery,dine_in',
            'payment_method' => 'nullable|in:cash,credit_card,debit_card,pix,other',
            'customer_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $establishment = Establishment::findOrFail($request->establishment_id);
        
        // Calculate totals
        $items = [];
        $subtotal = 0;

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            
            if (!$product->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => "Product {$product->name} is not available",
                ], 400);
            }

            $quantity = $item['quantity'];
            $price = $product->price;
            $itemTotal = $price * $quantity;

            $items[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'price' => $price,
                'total' => $itemTotal,
            ];

            $subtotal += $itemTotal;
        }

        $deliveryFee = $request->type === 'delivery' ? ($establishment->delivery_fee ?? 5.00) : 0;
        $discount = 0; // TODO: Apply promotions
        $total = $subtotal + $deliveryFee - $discount;

        // Create order
        $order = Order::create([
            'user_id' => Auth::id(),
            'establishment_id' => $request->establishment_id,
            'order_number' => Order::generateOrderNumber(),
            'status' => 'pending',
            'type' => $request->type,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'delivery_address' => $request->delivery_address,
            'items' => $items,
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'discount' => $discount,
            'total' => $total,
            'payment_method' => $request->payment_method,
            'customer_notes' => $request->customer_notes,
        ]);

        // Dispatch jobs to process order in background
        ProcessOrderJob::dispatch($order);
        SendOrderConfirmationJob::dispatch($order);
        UpdateInventoryJob::dispatch($order);
        NotifyEstablishmentJob::dispatch($order);

        return response()->json([
            'success' => true,
            'message' => 'Order created successfully',
            'order' => $order->load(['establishment', 'user']),
        ], 201);
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        // Check authorization
        if ($order->user_id !== Auth::id() && 
            ($order->establishment->user_id ?? null) !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'order' => $order->load(['establishment', 'user']),
        ]);
    }

    /**
     * Update order status (for establishment owners)
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Check if user owns the establishment
        if (($order->establishment->user_id ?? null) !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:confirmed,preparing,ready,delivered,cancelled',
            'cancellation_reason' => 'required_if:status,cancelled|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $order->updateStatus($request->status, $request->cancellation_reason ?? null);

        // Send notification to customer
        $this->notificationService->notifyOrderStatusChange($order);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
            'order' => $order->load(['establishment', 'user']),
        ]);
    }

    /**
     * Cancel order (for customers)
     */
    public function cancel(Request $request, Order $order)
    {
        // Check authorization
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        if (!$order->canBeCancelled()) {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be cancelled at this stage',
            ], 400);
        }

        $order->updateStatus('cancelled', $request->reason ?? 'Cancelled by customer');

        // Send notification to establishment
        $this->notificationService->notifyOrderStatusChange($order);

        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully',
            'order' => $order->load(['establishment', 'user']),
        ]);
    }
}


