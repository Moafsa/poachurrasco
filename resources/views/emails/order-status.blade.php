@extends('emails.layout')

@section('title', 'Status do Pedido Atualizado')

@section('content')
    @php
        $statusConfig = [
            'confirmed' => ['title' => 'Pedido Confirmado', 'color' => '#10b981', 'icon' => '✓'],
            'preparing' => ['title' => 'Pedido em Preparação', 'color' => '#f59e0b', 'icon' => '⏳'],
            'ready' => ['title' => 'Pedido Pronto', 'color' => '#3b82f6', 'icon' => '🎉'],
            'delivered' => ['title' => 'Pedido Entregue', 'color' => '#10b981', 'icon' => '✅'],
            'cancelled' => ['title' => 'Pedido Cancelado', 'color' => '#ef4444', 'icon' => '❌'],
        ];
        $config = $statusConfig[$status] ?? ['title' => 'Status Atualizado', 'color' => '#6b7280', 'icon' => '📦'];
    @endphp

    <h2 style="color: #1f2937; margin-top: 0;">{{ $config['title'] }}</h2>
    
    <div style="text-align: center; margin: 30px 0;">
        <div style="font-size: 64px; margin-bottom: 10px;">{{ $config['icon'] }}</div>
        <p style="color: #4b5563; font-size: 18px; margin: 0;">
            Pedido #{{ $order->order_number }}
        </p>
    </div>

    <div style="background-color: #f9fafb; border-radius: 8px; padding: 20px; margin: 20px 0;">
        <p style="color: #1f2937; margin: 0 0 10px 0; font-weight: 600;">Detalhes do Pedido:</p>
        <p style="color: #4b5563; margin: 5px 0;">
            <strong>Estabelecimento:</strong> {{ $establishment->name ?? 'N/A' }}
        </p>
        <p style="color: #4b5563; margin: 5px 0;">
            <strong>Valor Total:</strong> R$ {{ number_format($order->total, 2, ',', '.') }}
        </p>
        <p style="color: #4b5563; margin: 5px 0;">
            <strong>Status:</strong> <span style="color: {{ $config['color'] }}; font-weight: 600;">{{ $config['title'] }}</span>
        </p>
    </div>

    @if($status === 'ready')
        <p style="color: #059669; font-weight: 600; text-align: center; padding: 15px; background-color: #d1fae5; border-radius: 6px;">
            Seu pedido está pronto para retirada! 🎉
        </p>
    @endif

    <div class="divider"></div>
    <p style="text-align: center;">
        <a href="{{ url('/dashboard/orders/' . $order->id) }}" class="button">Ver Detalhes do Pedido</a>
    </p>
@endsection

















