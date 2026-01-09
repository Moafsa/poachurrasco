<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Order $order
    ) {
        $this->onQueue('orders');
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationService $notificationService): void
    {
        try {
            Log::info('Processing order', ['order_id' => $this->order->id, 'order_number' => $this->order->order_number]);

            // Update inventory
            $this->updateInventory();

            // Send notifications
            $notificationService->notifyNewOrder($this->order);

            // Update order status to confirmed
            $this->order->updateStatus('confirmed');

            Log::info('Order processed successfully', ['order_id' => $this->order->id]);
        } catch (\Exception $e) {
            Log::error('Error processing order', [
                'order_id' => $this->order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Update inventory for order items
     */
    protected function updateInventory(): void
    {
        foreach ($this->order->items as $item) {
            $product = \App\Models\Product::find($item['product_id']);
            
            if ($product && $product->track_stock) {
                $product->reduceStock($item['quantity']);
            }
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Order processing failed', [
            'order_id' => $this->order->id,
            'error' => $exception->getMessage()
        ]);

        // Update order status to indicate processing failure
        $this->order->update([
            'internal_notes' => ($this->order->internal_notes ?? '') . "\n[ERROR] Processing failed: " . $exception->getMessage()
        ]);
    }
}


















