<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Order $order,
        public string $status
    ) {
        $this->onQueue('notifications');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $statusMessages = [
            'confirmed' => 'Pedido Confirmado',
            'preparing' => 'Pedido em Preparação',
            'ready' => 'Pedido Pronto para Retirada',
            'delivered' => 'Pedido Entregue',
            'cancelled' => 'Pedido Cancelado',
        ];

        $subject = $statusMessages[$this->status] ?? 'Status do Pedido Atualizado';

        return new Envelope(
            subject: "{$subject} - Pedido #{$this->order->order_number}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-status',
            with: [
                'order' => $this->order,
                'status' => $this->status,
                'establishment' => $this->order->establishment,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

















