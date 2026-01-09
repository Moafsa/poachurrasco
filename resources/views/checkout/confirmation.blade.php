@extends('layouts.app')

@section('title', 'Pedido Confirmado')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm p-8 text-center">
            <!-- Success Icon -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-4">Pedido Confirmado!</h1>
            <p class="text-lg text-gray-600 mb-8">
                Seu pedido foi recebido e está sendo processado. Você receberá uma confirmação por e-mail em breve.
            </p>

            <!-- Order Details -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8 text-left">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Detalhes do Pedido</h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Número do Pedido:</span>
                        <span class="font-semibold text-gray-900">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Estabelecimento:</span>
                        <span class="font-semibold text-gray-900">{{ $order->establishment->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tipo de Entrega:</span>
                        <span class="font-semibold text-gray-900">
                            @if($order->type === 'pickup')
                                Retirada no Local
                            @elseif($order->type === 'delivery')
                                Entrega
                            @else
                                No Local
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Método de Pagamento:</span>
                        <span class="font-semibold text-gray-900">
                            @if($order->payment_method === 'pix')
                                PIX
                            @elseif($order->payment_method === 'credit_card')
                                Cartão de Crédito
                            @elseif($order->payment_method === 'debit_card')
                                Cartão de Débito
                            @elseif($order->payment_method === 'cash')
                                Dinheiro
                            @else
                                {{ $order->payment_method }}
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between text-lg font-bold pt-3 border-t">
                        <span class="text-gray-900">Total:</span>
                        <span class="text-churrasco-600">{{ $order->formatted_total }}</span>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8 text-left">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Itens do Pedido</h2>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                        <div class="flex justify-between">
                            <span class="text-gray-700">{{ $item['product_name'] }} x{{ $item['quantity'] }}</span>
                            <span class="font-semibold text-gray-900">R$ {{ number_format($item['total'], 2, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 bg-churrasco-500 text-white rounded-lg font-semibold hover:bg-churrasco-600 transition-colors">
                    Ver Meus Pedidos
                </a>
                <a href="{{ route('products') }}" class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 transition-colors">
                    Continuar Comprando
                </a>
            </div>
        </div>
    </div>
</div>
@endsection


















