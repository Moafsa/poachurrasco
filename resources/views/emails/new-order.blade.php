@extends('emails.layout')

@section('title', 'Novo Pedido Recebido')

@section('content')
    <h2 style="color: #1f2937; margin-top: 0;">Novo Pedido Recebido! 🎉</h2>
    
    <p style="color: #4b5563; font-size: 16px;">
        Você recebeu um novo pedido que precisa ser processado.
    </p>

    <div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0; border-radius: 4px;">
        <p style="color: #92400e; margin: 0; font-weight: 600;">
            📦 Pedido #{{ $order->order_number }}
        </p>
    </div>

    <div style="background-color: #f9fafb; border-radius: 8px; padding: 20px; margin: 20px 0;">
        <p style="color: #1f2937; margin: 0 0 15px 0; font-weight: 600; font-size: 18px;">Informações do Pedido:</p>
        
        <p style="color: #4b5563; margin: 10px 0;">
            <strong>Cliente:</strong> {{ $order->customer_name ?? 'N/A' }}
        </p>
        
        @if($order->customer_email)
        <p style="color: #4b5563; margin: 10px 0;">
            <strong>Email:</strong> {{ $order->customer_email }}
        </p>
        @endif

        @if($order->customer_phone)
        <p style="color: #4b5563; margin: 10px 0;">
            <strong>Telefone:</strong> {{ $order->customer_phone }}
        </p>
        @endif

        <p style="color: #4b5563; margin: 10px 0;">
            <strong>Valor Total:</strong> <span style="font-size: 20px; font-weight: 600; color: #f97316;">R$ {{ number_format($order->total, 2, ',', '.') }}</span>
        </p>

        <p style="color: #4b5563; margin: 10px 0;">
            <strong>Data:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
        </p>
    </div>

    @if($order->delivery_address)
    <div style="background-color: #f0f9ff; border-radius: 8px; padding: 15px; margin: 20px 0;">
        <p style="color: #1f2937; margin: 0 0 10px 0; font-weight: 600;">📍 Endereço de Entrega:</p>
        <p style="color: #4b5563; margin: 0;">{{ $order->delivery_address }}</p>
    </div>
    @endif

    <div class="divider"></div>
    
    <p style="color: #6b7280; font-size: 14px; text-align: center;">
        Acesse o dashboard para processar este pedido.
    </p>

    <p style="text-align: center;">
        <a href="{{ url('/dashboard/orders/' . $order->id) }}" class="button">Gerenciar Pedido</a>
    </p>
@endsection

















