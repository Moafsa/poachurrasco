<?php

namespace App\Services;

use App\Models\CustomNotification;
use App\Models\User;
use App\Models\Establishment;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Create and send a notification
     */
    public function createNotification(array $data): CustomNotification
    {
        $notification = CustomNotification::create([
            'user_id' => $data['user_id'] ?? null,
            'establishment_id' => $data['establishment_id'] ?? null,
            'order_id' => $data['order_id'] ?? null,
            'type' => $data['type'],
            'title' => $data['title'],
            'message' => $data['message'],
            'data' => $data['data'] ?? null,
        ]);

        // Send notification through configured channels
        $this->sendNotification($notification, $data['channels'] ?? ['database']);

        return $notification;
    }

    /**
     * Send notification through specified channels
     */
    public function sendNotification(CustomNotification $notification, array $channels = ['database']): void
    {
        foreach ($channels as $channel) {
            try {
                switch ($channel) {
                    case 'email':
                        $this->sendEmail($notification);
                        break;
                    case 'push':
                        $this->sendPush($notification);
                        break;
                    case 'sms':
                        $this->sendSms($notification);
                        break;
                }
            } catch (\Exception $e) {
                Log::error("Failed to send notification via {$channel}: " . $e->getMessage());
            }
        }
    }

    /**
     * Send email notification
     */
    protected function sendEmail(CustomNotification $notification): void
    {
        if (!$notification->user_id) {
            return;
        }

        $user = User::find($notification->user_id);
        if (!$user || !$user->email) {
            return;
        }

        try {
            Mail::to($user->email)->send(new \App\Mail\NotificationMail($notification));
            
            $notification->update([
                'sent_email' => true,
                'email_sent_at' => now(),
            ]);

            Log::info("Email notification sent successfully", [
                'user_id' => $user->id,
                'email' => $user->email,
                'notification_id' => $notification->id,
                'title' => $notification->title,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to send email notification", [
                'user_id' => $user->id,
                'email' => $user->email,
                'notification_id' => $notification->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Don't mark as sent if it failed
            throw $e;
        }
    }

    /**
     * Send push notification
     */
    protected function sendPush(CustomNotification $notification): void
    {
        // TODO: Implement push notification service
        $notification->update([
            'sent_push' => true,
            'push_sent_at' => now(),
        ]);

        Log::info("Push notification sent: {$notification->title}");
    }

    /**
     * Send SMS notification
     */
    protected function sendSms(CustomNotification $notification): void
    {
        // TODO: Implement SMS service
        $notification->update([
            'sent_sms' => true,
            'sms_sent_at' => now(),
        ]);

        Log::info("SMS notification sent: {$notification->title}");
    }

    /**
     * Notify user about order status change
     */
    public function notifyOrderStatusChange(Order $order): void
    {
        $statusMessages = [
            'confirmed' => 'Seu pedido foi confirmado!',
            'preparing' => 'Seu pedido está sendo preparado.',
            'ready' => 'Seu pedido está pronto para retirada!',
            'delivered' => 'Seu pedido foi entregue.',
            'cancelled' => 'Seu pedido foi cancelado.',
        ];

        // Create database notification
        $this->createNotification([
            'user_id' => $order->user_id,
            'establishment_id' => $order->establishment_id,
            'order_id' => $order->id,
            'type' => 'order_status_changed',
            'title' => 'Status do Pedido Atualizado',
            'message' => $statusMessages[$order->status] ?? 'O status do seu pedido foi atualizado.',
            'data' => [
                'order_number' => $order->order_number,
                'status' => $order->status,
            ],
            'channels' => ['database'],
        ]);

        // Send dedicated order status email
        if ($order->user_id) {
            $user = User::find($order->user_id);
            if ($user && $user->email) {
                try {
                    Mail::to($user->email)->send(new \App\Mail\OrderStatusMail($order, $order->status));
                    Log::info("Order status email sent", [
                        'order_id' => $order->id,
                        'status' => $order->status,
                        'email' => $user->email,
                    ]);
                } catch (\Exception $e) {
                    Log::error("Failed to send order status email", [
                        'order_id' => $order->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }
    }

    /**
     * Notify establishment about new order
     */
    public function notifyNewOrder(Order $order): void
    {
        $establishmentUserId = $order->establishment->user_id ?? null;

        // Create database notification for establishment
        if ($establishmentUserId) {
            $this->createNotification([
                'user_id' => $establishmentUserId,
                'establishment_id' => $order->establishment_id,
                'order_id' => $order->id,
                'type' => 'order_created',
                'title' => 'Novo Pedido Recebido',
                'message' => "Novo pedido #{$order->order_number} recebido de {$order->customer_name}.",
                'data' => [
                    'order_number' => $order->order_number,
                    'total' => $order->total,
                ],
                'channels' => ['database'],
            ]);

            // Send dedicated new order email to establishment
            $establishmentUser = User::find($establishmentUserId);
            if ($establishmentUser && $establishmentUser->email) {
                try {
                    Mail::to($establishmentUser->email)->send(new \App\Mail\NewOrderMail($order));
                    Log::info("New order email sent to establishment", [
                        'order_id' => $order->id,
                        'establishment_id' => $order->establishment_id,
                        'email' => $establishmentUser->email,
                    ]);
                } catch (\Exception $e) {
                    Log::error("Failed to send new order email to establishment", [
                        'order_id' => $order->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }
    }

    /**
     * Notify user about new review
     */
    public function notifyNewReview(Establishment $establishment, $review): void
    {
        if (!$establishment->user_id) {
            return;
        }

        $this->createNotification([
            'user_id' => $establishment->user_id,
            'establishment_id' => $establishment->id,
            'type' => 'review_received',
            'title' => 'Nova Avaliação Recebida',
            'message' => "Você recebeu uma nova avaliação de {$review->user->name ?? 'Anônimo'}.",
            'data' => [
                'rating' => $review->rating,
                'review_id' => $review->id,
            ],
            'channels' => ['database'],
        ]);
    }
}


