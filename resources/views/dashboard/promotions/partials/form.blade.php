@php
    use Illuminate\Support\Facades\Storage;

    $channelsValue = is_array(old('channels')) ? implode(', ', old('channels')) : (isset($promotion) ? implode(', ', $promotion->channels ?? []) : '');
    $selectedProducts = collect(old('applicable_products', $promotion->applicable_products ?? []))->map(fn($id) => (int) $id)->all();
@endphp

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-8">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title', $promotion->title ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500"
                       required>
            </div>
            <div>
                <label for="establishment_id" class="block text-sm font-medium text-gray-700">Establishment</label>
                <select id="establishment_id" name="establishment_id"
                        class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                    @foreach($establishments as $establishmentId => $establishmentName)
                        <option value="{{ $establishmentId }}" @selected((int) old('establishment_id', $promotion->establishment_id ?? '') === (int) $establishmentId)>
                            {{ $establishmentName }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div>
                <label for="promotion_type" class="block text-sm font-medium text-gray-700">Promotion type</label>
                <select id="promotion_type" name="promotion_type"
                        class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                    @foreach($types as $type)
                        <option value="{{ $type }}" @selected(old('promotion_type', $promotion->promotion_type ?? '') === $type)>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="discount_value" class="block text-sm font-medium text-gray-700">Discount value</label>
                <input type="number" step="0.01" id="discount_value" name="discount_value"
                       value="{{ old('discount_value', $promotion->discount_value ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500" required>
            </div>
            <div>
                <label for="minimum_order_value" class="block text-sm font-medium text-gray-700">Minimum order</label>
                <input type="number" step="0.01" id="minimum_order_value" name="minimum_order_value"
                       value="{{ old('minimum_order_value', $promotion->minimum_order_value ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div>
                <label for="promo_code" class="block text-sm font-medium text-gray-700">Promo code</label>
                <input type="text" id="promo_code" name="promo_code" value="{{ old('promo_code', $promotion->promo_code ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
            <div>
                <label for="usage_limit" class="block text-sm font-medium text-gray-700">Usage limit</label>
                <input type="number" id="usage_limit" name="usage_limit" value="{{ old('usage_limit', $promotion->usage_limit ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status"
                        class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" @selected(old('status', $promotion->status ?? '') === $status)>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label for="starts_at" class="block text-sm font-medium text-gray-700">Starts at</label>
                <input type="datetime-local" id="starts_at" name="starts_at"
                       value="{{ old('starts_at', optional($promotion->starts_at ?? null)?->format('Y-m-d\TH:i')) }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
            <div>
                <label for="ends_at" class="block text-sm font-medium text-gray-700">Ends at</label>
                <input type="datetime-local" id="ends_at" name="ends_at"
                       value="{{ old('ends_at', optional($promotion->ends_at ?? null)?->format('Y-m-d\TH:i')) }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" rows="3"
                      class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">{{ old('description', $promotion->description ?? '') }}</textarea>
        </div>

        <div>
            <label for="applicable_products" class="block text-sm font-medium text-gray-700">Applicable products</label>
            <select id="applicable_products" name="applicable_products[]" multiple
                    class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500 h-40">
                @foreach($products as $product)
                    <option value="{{ $product->id }}" @selected(in_array($product->id, $selectedProducts, true))>
                        {{ $product->name }} — {{ $product->establishment->name ?? 'Shared' }}
                    </option>
                @endforeach
            </select>
            <p class="mt-2 text-xs text-gray-500">Select one or more products. Leave empty to apply to the full catalog.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $promotion->is_featured ?? false))
                       class="rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                <span class="ml-2 text-sm text-gray-700">Feature this promotion</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_stackable" value="1" @checked(old('is_stackable', $promotion->is_stackable ?? false))
                       class="rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                <span class="ml-2 text-sm text-gray-700">Allow stacking with other promotions</span>
            </label>
        </div>

        <div>
            <label for="channels" class="block text-sm font-medium text-gray-700">Channels</label>
            <input type="text" id="channels" name="channels" value="{{ $channelsValue }}"
                   placeholder="Website, App, Counter..."
                   class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            <p class="mt-2 text-xs text-gray-500">Separate values with comma.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Banner image</label>
                <input type="file" name="banner_image" accept="image/*"
                       class="mt-2 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-churrasco-50 file:text-churrasco-600 hover:file:bg-churrasco-100">
                @if(!empty($promotion?->banner_image))
                    <img src="{{ Storage::disk('public')->url($promotion->banner_image) }}" alt="Promotion banner"
                         class="mt-4 w-full h-40 object-cover rounded-xl border border-gray-200">
                @endif
            </div>
            <div>
                <label for="terms" class="block text-sm font-medium text-gray-700">Terms and conditions</label>
                <textarea id="terms" name="terms" rows="5"
                          class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">{{ old('terms', $promotion->terms ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-between">
        <a href="{{ route('promotions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors duration-200">
            Cancel
        </a>
        <button type="submit" class="inline-flex items-center px-6 py-3 bg-churrasco-600 text-white rounded-xl hover:bg-churrasco-700 transition-colors duration-200">
            {{ $submitLabel }}
        </button>
    </div>
</form>












