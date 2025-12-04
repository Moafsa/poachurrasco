@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Finalizar Pedido</h1>

        <form id="checkout-form" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            
            <!-- Checkout Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer Information -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Informações de Contato</h2>
                    <div class="space-y-4">
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">Nome Completo *</label>
                            <input type="text" id="customer_name" name="customer_name" required
                                   value="{{ auth()->user()->name ?? '' }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">E-mail</label>
                                <input type="email" id="customer_email" name="customer_email"
                                       value="{{ auth()->user()->email ?? '' }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                            </div>
                            <div>
                                <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">Telefone *</label>
                                <input type="tel" id="customer_phone" name="customer_phone" required
                                       value="{{ auth()->user()->phone ?? '' }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500"
                                       placeholder="(51) 99999-9999">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivery Type -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Tipo de Entrega</h2>
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-churrasco-500 transition-colors">
                            <input type="radio" name="type" value="pickup" checked class="mr-3 text-churrasco-500 focus:ring-churrasco-500">
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900">Retirada no Local</div>
                                <div class="text-sm text-gray-600">Retire seu pedido no estabelecimento</div>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-churrasco-500 transition-colors">
                            <input type="radio" name="type" value="delivery" class="mr-3 text-churrasco-500 focus:ring-churrasco-500">
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900">Entrega</div>
                                <div class="text-sm text-gray-600">Entregamos no endereço informado</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Delivery Address -->
                <div id="delivery-address-section" class="bg-white rounded-lg shadow-sm p-6 hidden">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Endereço de Entrega</h2>
                    <div class="space-y-4">
                        <div>
                            <label for="delivery_address" class="block text-sm font-medium text-gray-700 mb-2">Endereço Completo *</label>
                            <textarea id="delivery_address" name="delivery_address" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500"
                                      placeholder="Rua, número, complemento, bairro"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Método de Pagamento</h2>
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-churrasco-500 transition-colors">
                            <input type="radio" name="payment_method" value="pix" checked class="mr-3 text-churrasco-500 focus:ring-churrasco-500">
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900">PIX</div>
                                <div class="text-sm text-gray-600">Pagamento instantâneo</div>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-churrasco-500 transition-colors">
                            <input type="radio" name="payment_method" value="credit_card" class="mr-3 text-churrasco-500 focus:ring-churrasco-500">
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900">Cartão de Crédito</div>
                                <div class="text-sm text-gray-600">Pagamento parcelado</div>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-churrasco-500 transition-colors">
                            <input type="radio" name="payment_method" value="debit_card" class="mr-3 text-churrasco-500 focus:ring-churrasco-500">
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900">Cartão de Débito</div>
                                <div class="text-sm text-gray-600">Débito em conta</div>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-churrasco-500 transition-colors">
                            <input type="radio" name="payment_method" value="cash" class="mr-3 text-churrasco-500 focus:ring-churrasco-500">
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900">Dinheiro</div>
                                <div class="text-sm text-gray-600">Pagamento na entrega</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Customer Notes -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Observações</h2>
                    <textarea id="customer_notes" name="customer_notes" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500"
                              placeholder="Alguma observação especial para o pedido?"></textarea>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm sticky top-24">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Resumo do Pedido</h2>
                        
                        <div id="checkout-items" class="space-y-3 mb-6">
                            <!-- Items will be loaded here -->
                        </div>

                        <div class="border-t pt-4 space-y-3 mb-6">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span id="checkout-subtotal">R$ 0,00</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Taxa de Entrega</span>
                                <span id="checkout-delivery-fee">R$ 0,00</span>
                            </div>
                            <div id="checkout-discount-row" class="hidden flex justify-between text-green-600">
                                <span>Desconto</span>
                                <span id="checkout-discount">-R$ 0,00</span>
                            </div>
                            <div class="border-t pt-3 flex justify-between text-lg font-bold text-gray-900">
                                <span>Total</span>
                                <span id="checkout-total">R$ 0,00</span>
                            </div>
                        </div>

                        <button type="submit" id="submit-order" class="w-full bg-churrasco-500 hover:bg-churrasco-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                            Confirmar Pedido
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/cart.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show/hide delivery address based on delivery type
        document.querySelectorAll('input[name="type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const deliverySection = document.getElementById('delivery-address-section');
                if (this.value === 'delivery') {
                    deliverySection.classList.remove('hidden');
                    document.getElementById('delivery_address').required = true;
                } else {
                    deliverySection.classList.add('hidden');
                    document.getElementById('delivery_address').required = false;
                }
                updateCheckoutTotals();
            });
        });

        // Load cart items and totals
        loadCheckoutData();

        // Handle form submission
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            e.preventDefault();
            submitOrder();
        });
    });

    function loadCheckoutData() {
        CartManager.getCart().then(data => {
            if (!data.success || data.items.length === 0) {
                window.location.href = '{{ route("cart.index") }}';
                return;
            }

            // Group items by establishment
            const itemsByEstablishment = {};
            data.items.forEach(item => {
                if (!itemsByEstablishment[item.establishment_id]) {
                    itemsByEstablishment[item.establishment_id] = {
                        establishment_name: item.establishment_name,
                        items: []
                    };
                }
                itemsByEstablishment[item.establishment_id].items.push(item);
            });

            // Set current establishment (use first one for now)
            if (data.items.length > 0) {
                currentEstablishmentId = data.items[0].establishment_id;
            }

            // Display items
            const itemsContainer = document.getElementById('checkout-items');
            itemsContainer.innerHTML = '';
            
            Object.values(itemsByEstablishment).forEach(establishment => {
                establishment.items.forEach(item => {
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'flex justify-between text-sm';
                    itemDiv.innerHTML = `
                        <span>${item.product_name} x${item.quantity}</span>
                        <span>R$ ${item.total.toFixed(2).replace('.', ',')}</span>
                    `;
                    itemsContainer.appendChild(itemDiv);
                });
            });

            updateCheckoutTotals();
        });
    }

    let currentEstablishmentId = null;

    function updateCheckoutTotals() {
        const deliveryType = document.querySelector('input[name="type"]:checked').value;
        
        CartManager.calculateTotals(deliveryType, currentEstablishmentId).then(data => {
            if (data.success) {
                document.getElementById('checkout-subtotal').textContent = 
                    'R$ ' + data.subtotal.toFixed(2).replace('.', ',');
                document.getElementById('checkout-delivery-fee').textContent = 
                    'R$ ' + data.delivery_fee.toFixed(2).replace('.', ',');
                document.getElementById('checkout-total').textContent = 
                    'R$ ' + data.total.toFixed(2).replace('.', ',');

                if (data.discount > 0) {
                    document.getElementById('checkout-discount-row').classList.remove('hidden');
                    document.getElementById('checkout-discount').textContent = 
                        '-R$ ' + data.discount.toFixed(2).replace('.', ',');
                } else {
                    document.getElementById('checkout-discount-row').classList.add('hidden');
                }
            }
        });
    }

    async function submitOrder() {
        const submitButton = document.getElementById('submit-order');
        submitButton.disabled = true;
        submitButton.textContent = 'Processando...';

        try {
            const cartData = await CartManager.getCart();
            if (!cartData.success || cartData.items.length === 0) {
                alert('Seu carrinho está vazio');
                window.location.href = '{{ route("cart.index") }}';
                return;
            }

            // Group items by establishment (for now, we'll use the first establishment)
            const firstItem = cartData.items[0];
            const establishmentId = firstItem.establishment_id;

            const formData = {
                establishment_id: establishmentId,
                items: cartData.items.map(item => ({
                    product_id: item.product_id,
                    quantity: item.quantity
                })),
                customer_name: document.getElementById('customer_name').value,
                customer_email: document.getElementById('customer_email').value,
                customer_phone: document.getElementById('customer_phone').value,
                customer_address: document.getElementById('delivery_address')?.value || '',
                delivery_address: document.getElementById('delivery_address').value || '',
                type: document.querySelector('input[name="type"]:checked').value,
                payment_method: document.querySelector('input[name="payment_method"]:checked').value,
                customer_notes: document.getElementById('customer_notes').value,
            };

            const response = await fetch('{{ route("api.orders.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (result.success) {
                // Clear cart
                await CartManager.clearCart();
                
                // Redirect to confirmation page
                window.location.href = `{{ url('/pedido') }}/${result.order.id}/confirmacao`;
            } else {
                alert(result.message || 'Erro ao processar pedido. Por favor, tente novamente.');
                submitButton.disabled = false;
                submitButton.textContent = 'Confirmar Pedido';
            }
        } catch (error) {
            console.error('Error submitting order:', error);
            alert('Erro ao processar pedido. Por favor, tente novamente.');
            submitButton.disabled = false;
            submitButton.textContent = 'Confirmar Pedido';
        }
    }
</script>
@endpush
@endsection

