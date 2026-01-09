@extends('layouts.app')

@section('title', 'Carrinho de Compras')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Carrinho de Compras</h1>

        <div id="cart-empty" class="hidden text-center py-12">
            <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <h2 class="text-2xl font-semibold text-gray-900 mb-2">Seu carrinho está vazio</h2>
            <p class="text-gray-600 mb-6">Adicione produtos ao seu carrinho para continuar comprando.</p>
            <a href="{{ route('products') }}" class="inline-flex items-center px-6 py-3 bg-churrasco-500 text-white rounded-lg font-semibold hover:bg-churrasco-600 transition-colors">
                Continuar Comprando
            </a>
        </div>

        <div id="cart-content" class="hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="p-6">
                            <div id="cart-items" class="space-y-4">
                                <!-- Cart items will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm sticky top-24">
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Resumo do Pedido</h2>
                            
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between text-gray-600">
                                    <span>Subtotal</span>
                                    <span id="cart-subtotal">R$ 0,00</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Taxa de Entrega</span>
                                    <span id="cart-delivery-fee">R$ 0,00</span>
                                </div>
                                <div id="cart-discount-row" class="hidden flex justify-between text-green-600">
                                    <span>Desconto</span>
                                    <span id="cart-discount">-R$ 0,00</span>
                                </div>
                                <div class="border-t pt-3 flex justify-between text-lg font-bold text-gray-900">
                                    <span>Total</span>
                                    <span id="cart-total">R$ 0,00</span>
                                </div>
                            </div>

                            <a href="{{ route('checkout.index') }}" id="checkout-button" class="w-full bg-churrasco-500 hover:bg-churrasco-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors text-center block">
                                Finalizar Compra
                            </a>

                            <a href="{{ route('products') }}" class="mt-4 w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-lg transition-colors text-center block">
                                Continuar Comprando
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/cart.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        CartManager.loadCart();
    });
</script>
@endpush
@endsection


















