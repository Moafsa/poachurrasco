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

class NotifyEstablishmentJob implements ShouldQueue
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
        $this->onQueue('notifications');
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationService $notificationService): void
    {
        try {
            Log::info('Notifying establishment of new order', [
                'order_id' => $this->order->id,
                'establishment_id' => $this->order->establishment_id
            ]);

            // Notify establishment owner
            $establishment = $this->order->establishment;
            if ($establishment && $establishment->user) {
                $notificationService->notifyNewOrder($this->order);
            }

            Log::info('Establishment notified successfully', ['order_id' => $this->order->id]);
        } catch (\Exception $e) {
            Log::error('Error notifying establishment', [
                'order_id' => $this->order->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}


















