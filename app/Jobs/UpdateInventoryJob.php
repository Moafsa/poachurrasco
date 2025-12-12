<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UpdateInventoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Order $order
    ) {
        $this->onQueue('inventory');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Updating inventory for order', ['order_id' => $this->order->id]);

            DB::transaction(function () {
                foreach ($this->order->items as $item) {
                    $product = \App\Models\Product::find($item['product_id']);
                    
                    if ($product && $product->track_stock) {
                        $oldStock = $product->stock_quantity;
                        $product->reduceStock($item['quantity']);
                        
                        Log::info('Inventory updated', [
                            'product_id' => $product->id,
                            'old_stock' => $oldStock,
                            'new_stock' => $product->fresh()->stock_quantity,
                            'quantity_removed' => $item['quantity']
                        ]);
                    }
                }
            });

            Log::info('Inventory updated successfully', ['order_id' => $this->order->id]);
        } catch (\Exception $e) {
            Log::error('Error updating inventory', [
                'order_id' => $this->order->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}

















