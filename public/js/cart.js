/**
 * Cart Manager - Handles cart operations
 */
const CartManager = {
    /**
     * Get cart items from server
     */
    async getCart() {
        try {
            const response = await fetch('/api/cart');
            return await response.json();
        } catch (error) {
            console.error('Error fetching cart:', error);
            return { success: false, items: [], count: 0 };
        }
    },

    /**
     * Add product to cart
     */
    async addToCart(productId, quantity = 1) {
        try {
            const response = await fetch('/api/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            });

            const result = await response.json();
            
            if (result.success) {
                this.updateCartCount(result.cart_count);
                this.dispatchCartUpdate();
            }

            return result;
        } catch (error) {
            console.error('Error adding to cart:', error);
            return { success: false, message: 'Erro ao adicionar ao carrinho' };
        }
    },

    /**
     * Update item quantity in cart
     */
    async updateQuantity(productId, quantity) {
        try {
            const response = await fetch('/api/cart/update', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            });

            const result = await response.json();
            
            if (result.success) {
                this.dispatchCartUpdate();
            }

            return result;
        } catch (error) {
            console.error('Error updating cart:', error);
            return { success: false, message: 'Erro ao atualizar carrinho' };
        }
    },

    /**
     * Remove item from cart
     */
    async removeFromCart(productId) {
        try {
            const response = await fetch(`/api/cart/remove/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.getCsrfToken()
                }
            });

            const result = await response.json();
            
            if (result.success) {
                this.updateCartCount(result.cart_count);
                this.dispatchCartUpdate();
            }

            return result;
        } catch (error) {
            console.error('Error removing from cart:', error);
            return { success: false, message: 'Erro ao remover do carrinho' };
        }
    },

    /**
     * Clear cart
     */
    async clearCart() {
        try {
            const response = await fetch('/api/cart/clear', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.getCsrfToken()
                }
            });

            const result = await response.json();
            
            if (result.success) {
                this.updateCartCount(0);
                this.dispatchCartUpdate();
            }

            return result;
        } catch (error) {
            console.error('Error clearing cart:', error);
            return { success: false, message: 'Erro ao limpar carrinho' };
        }
    },

    /**
     * Get cart count
     */
    async getCartCount() {
        try {
            const response = await fetch('/api/cart/count');
            const result = await response.json();
            return result.count || 0;
        } catch (error) {
            console.error('Error getting cart count:', error);
            return 0;
        }
    },

    /**
     * Calculate totals
     */
    async calculateTotals(deliveryType = 'pickup', establishmentId = null, promoCode = null) {
        try {
            const response = await fetch('/api/cart/calculate-totals', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify({
                    type: deliveryType,
                    establishment_id: establishmentId,
                    promo_code: promoCode
                })
            });

            return await response.json();
        } catch (error) {
            console.error('Error calculating totals:', error);
            return { success: false };
        }
    },

    /**
     * Load cart and display it
     */
    async loadCart() {
        const data = await this.getCart();
        
        if (!data.success || data.items.length === 0) {
            this.showEmptyCart();
            return;
        }

        this.showCartContent(data);
    },

    /**
     * Show empty cart message
     */
    showEmptyCart() {
        const emptyDiv = document.getElementById('cart-empty');
        const contentDiv = document.getElementById('cart-content');
        
        if (emptyDiv) emptyDiv.classList.remove('hidden');
        if (contentDiv) contentDiv.classList.add('hidden');
    },

    /**
     * Show cart content
     */
    showCartContent(data) {
        const emptyDiv = document.getElementById('cart-empty');
        const contentDiv = document.getElementById('cart-content');
        
        if (emptyDiv) emptyDiv.classList.add('hidden');
        if (contentDiv) contentDiv.classList.remove('hidden');

        // Display items
        const itemsContainer = document.getElementById('cart-items');
        if (itemsContainer) {
            itemsContainer.innerHTML = '';
            
            data.items.forEach(item => {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'flex items-center space-x-4 py-4 border-b border-gray-200 last:border-0';
                itemDiv.innerHTML = `
                    <div class="flex-shrink-0 w-20 h-20 bg-gray-200 rounded-lg overflow-hidden">
                        ${item.product_image ? 
                            `<img src="/storage/${item.product_image}" alt="${item.product_name}" class="w-full h-full object-cover">` :
                            `<div class="w-full h-full flex items-center justify-center text-gray-400">🥩</div>`
                        }
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">${item.product_name}</h3>
                        <p class="text-sm text-gray-600">${item.establishment_name}</p>
                        <div class="mt-2 flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <button onclick="CartManager.updateItemQuantity(${item.product_id}, ${item.quantity - 1})" 
                                        class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <span class="w-12 text-center font-semibold">${item.quantity}</span>
                                <button onclick="CartManager.updateItemQuantity(${item.product_id}, ${item.quantity + 1})" 
                                        class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                            </div>
                            <button onclick="CartManager.removeItem(${item.product_id})" 
                                    class="text-red-600 hover:text-red-700 text-sm font-medium">
                                Remover
                            </button>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-gray-900">R$ ${item.total.toFixed(2).replace('.', ',')}</div>
                        <div class="text-sm text-gray-600">R$ ${item.price.toFixed(2).replace('.', ',')} cada</div>
                    </div>
                `;
                itemsContainer.appendChild(itemDiv);
            });
        }

        // Update totals
        this.updateCartTotals(data.subtotal);
    },

    /**
     * Update cart totals
     */
    async updateCartTotals(subtotal) {
        const totals = await this.calculateTotals('pickup');
        
        if (totals.success) {
            const subtotalEl = document.getElementById('cart-subtotal');
            const deliveryFeeEl = document.getElementById('cart-delivery-fee');
            const discountEl = document.getElementById('cart-discount');
            const discountRowEl = document.getElementById('cart-discount-row');
            const totalEl = document.getElementById('cart-total');

            if (subtotalEl) subtotalEl.textContent = 'R$ ' + totals.subtotal.toFixed(2).replace('.', ',');
            if (deliveryFeeEl) deliveryFeeEl.textContent = 'R$ ' + totals.delivery_fee.toFixed(2).replace('.', ',');
            if (totalEl) totalEl.textContent = 'R$ ' + totals.total.toFixed(2).replace('.', ',');

            if (totals.discount > 0) {
                if (discountEl) discountEl.textContent = '-R$ ' + totals.discount.toFixed(2).replace('.', ',');
                if (discountRowEl) discountRowEl.classList.remove('hidden');
            } else {
                if (discountRowEl) discountRowEl.classList.add('hidden');
            }
        }
    },

    /**
     * Update item quantity helper
     */
    async updateItemQuantity(productId, newQuantity) {
        if (newQuantity < 1) {
            await this.removeFromCart(productId);
        } else {
            await this.updateQuantity(productId, newQuantity);
        }
        await this.loadCart();
    },

    /**
     * Remove item helper
     */
    async removeItem(productId) {
        if (confirm('Deseja remover este item do carrinho?')) {
            await this.removeFromCart(productId);
            await this.loadCart();
        }
    },

    /**
     * Update cart count in navbar
     */
    async updateCartCount(count) {
        // Update all cart count elements
        const countElements = [
            document.getElementById('cart-count'),
            document.getElementById('cart-count-mobile'),
            document.getElementById('cart-count-bottom')
        ];

        countElements.forEach(element => {
            if (element) {
                element.textContent = count;
                if (count > 0) {
                    element.classList.remove('hidden');
                } else {
                    element.classList.add('hidden');
                }
            }
        });
    },

    /**
     * Dispatch cart update event
     */
    dispatchCartUpdate() {
        const event = new CustomEvent('cartUpdated');
        window.dispatchEvent(event);
    },

    /**
     * Get CSRF token
     */
    getCsrfToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.getAttribute('content') : '';
    },

    /**
     * Sync cart after login (called when user authenticates)
     */
    async syncAfterLogin() {
        try {
            // Reload cart to get database cart
            await this.loadCart();
            const count = await this.getCartCount();
            this.updateCartCount(count);
            this.dispatchCartUpdate();
        } catch (error) {
            console.error('Error syncing cart after login:', error);
        }
    },

    /**
     * Initialize cart count on page load
     */
    async init() {
        const count = await this.getCartCount();
        this.updateCartCount(count);
        
        // Listen for authentication events
        window.addEventListener('userAuthenticated', () => {
            this.syncAfterLogin();
        });
    }
};

// Initialize on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => CartManager.init());
} else {
    CartManager.init();
}

