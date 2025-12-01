@php
    use Illuminate\Support\Facades\Storage;

    $tagsValue = old('tags', isset($product) ? implode(', ', $product->tags ?? []) : '');
    $videosValue = old('videos', isset($product) ? implode(' ', $product->videos ?? []) : '');
    $dimensions = old('dimensions', isset($product) ? $product->dimensions : []);
@endphp

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-8">
    @csrf
    @if ($method === 'PUT')
        @method('PUT')
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $product->name ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500"
                       required>
            </div>

            <div>
                <label for="establishment_id" class="block text-sm font-medium text-gray-700">Establishment</label>
                <select id="establishment_id" name="establishment_id" class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                    @foreach($establishments as $establishmentId => $establishmentName)
                        <option value="{{ $establishmentId }}" @selected((int) old('establishment_id', $product->establishment_id ?? '') === (int) $establishmentId)>
                            {{ $establishmentName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                <select id="category" name="category" class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500" required>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" @selected(old('category', $product->category ?? '') === $category)>
                            {{ ucfirst(str_replace('_', ' ', $category)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="subcategory" class="block text-sm font-medium text-gray-700">Subcategory</label>
                <input type="text" id="subcategory" name="subcategory" value="{{ old('subcategory', $product->subcategory ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                <input type="number" step="0.01" id="price" name="price" value="{{ old('price', $product->price ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500"
                       required>
            </div>
            <div>
                <label for="compare_price" class="block text-sm font-medium text-gray-700">Compare Price</label>
                <input type="number" step="0.01" id="compare_price" name="compare_price" value="{{ old('compare_price', $product->compare_price ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
            <div>
                <label for="cost_price" class="block text-sm font-medium text-gray-700">Cost Price</label>
                <input type="number" step="0.01" id="cost_price" name="cost_price" value="{{ old('cost_price', $product->cost_price ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div>
                <label for="sku" class="block text-sm font-medium text-gray-700">SKU</label>
                <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
            <div>
                <label for="barcode" class="block text-sm font-medium text-gray-700">Barcode</label>
                <input type="text" id="barcode" name="barcode" value="{{ old('barcode', $product->barcode ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
            <div>
                <label for="brand" class="block text-sm font-medium text-gray-700">Brand</label>
                <input type="text" id="brand" name="brand" value="{{ old('brand', $product->brand ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
            <div>
                <label for="origin" class="block text-sm font-medium text-gray-700">Origin</label>
                <input type="text" id="origin" name="origin" value="{{ old('origin', $product->origin ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div>
                <label for="weight" class="block text-sm font-medium text-gray-700">Weight (kg)</label>
                <input type="number" step="0.001" id="weight" name="weight" value="{{ old('weight', $product->weight ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Dimensions (cm)</label>
                <div class="mt-2 grid grid-cols-3 gap-3">
                    <input type="number" step="0.01" name="dimensions[length]" placeholder="Length" value="{{ $dimensions['length'] ?? '' }}"
                           class="rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                    <input type="number" step="0.01" name="dimensions[width]" placeholder="Width" value="{{ $dimensions['width'] ?? '' }}"
                           class="rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                    <input type="number" step="0.01" name="dimensions[height]" placeholder="Height" value="{{ $dimensions['height'] ?? '' }}"
                           class="rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                </div>
            </div>
            <div class="space-y-3">
                <label class="block text-sm font-medium text-gray-700">Inventory</label>
                <div class="flex items-center space-x-3">
                    <input type="number" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}"
                           class="w-32 rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                    <input type="number" id="low_stock_threshold" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold ?? 5) }}"
                           class="w-32 rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500" placeholder="Low stock">
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <label class="inline-flex items-center">
                <input type="checkbox" name="track_stock" value="1" @checked(old('track_stock', $product->track_stock ?? true)) class="rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                <span class="ml-2 text-sm text-gray-700">Track stock</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" name="allow_backorder" value="1" @checked(old('allow_backorder', $product->allow_backorder ?? false)) class="rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                <span class="ml-2 text-sm text-gray-700">Allow backorder</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured ?? false)) class="rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                <span class="ml-2 text-sm text-gray-700">Featured product</span>
            </label>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true)) class="rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                <span class="ml-2 text-sm text-gray-700">Product is active</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_service" value="1" @checked(old('is_service', $product->is_service ?? false)) class="rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                <span class="ml-2 text-sm text-gray-700">This product is a service</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_digital" value="1" @checked(old('is_digital', $product->is_digital ?? false)) class="rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                <span class="ml-2 text-sm text-gray-700">Digital product</span>
            </label>
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" rows="4"
                      class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">{{ old('description', $product->description ?? '') }}</textarea>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                <input type="text" id="tags" name="tags" value="{{ is_array(old('tags')) ? implode(', ', old('tags')) : $tagsValue }}"
                       placeholder="separate values by comma"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
            <div>
                <label for="videos" class="block text-sm font-medium text-gray-700">Video URLs</label>
                <input type="text" id="videos" name="videos" value="{{ is_array(old('videos')) ? implode(' ', old('videos')) : $videosValue }}"
                       placeholder="paste URLs separated by space"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label for="ingredients" class="block text-sm font-medium text-gray-700">Ingredients</label>
                <textarea id="ingredients" name="ingredients" rows="4" placeholder="One ingredient per line"
                          class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">{{ is_array(old('ingredients')) ? implode("\n", old('ingredients')) : (old('ingredients') ?? implode("\n", $product->ingredients ?? [])) }}</textarea>
            </div>
            <div>
                <label for="nutritional_info" class="block text-sm font-medium text-gray-700">Nutritional Info</label>
                <textarea id="nutritional_info" name="nutritional_info" rows="4" placeholder="One item per line"
                          class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">{{ is_array(old('nutritional_info')) ? implode("\n", old('nutritional_info')) : (old('nutritional_info') ?? implode("\n", $product->nutritional_info ?? [])) }}</textarea>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label for="allergens" class="block text-sm font-medium text-gray-700">Allergens</label>
                <textarea id="allergens" name="allergens" rows="4" placeholder="One allergen per line"
                          class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">{{ is_array(old('allergens')) ? implode("\n", old('allergens')) : (old('allergens') ?? implode("\n", $product->allergens ?? [])) }}</textarea>
            </div>
            <div>
                <label for="storage_instructions" class="block text-sm font-medium text-gray-700">Storage Instructions</label>
                <textarea id="storage_instructions" name="storage_instructions" rows="4"
                          class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">{{ old('storage_instructions', $product->storage_instructions ?? '') }}</textarea>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label for="expiry_date" class="block text-sm font-medium text-gray-700">Expiry Date</label>
                <input type="date" id="expiry_date" name="expiry_date" value="{{ old('expiry_date', optional($product->expiry_date ?? null)->format('Y-m-d')) }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
            <div>
                <label for="seo_score" class="block text-sm font-medium text-gray-700">SEO Score</label>
                <input type="number" id="seo_score" name="seo_score" min="0" max="100" value="{{ old('seo_score', $product->seo_score ?? 0) }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div>
                <label for="meta_title" class="block text-sm font-medium text-gray-700">Meta Title</label>
                <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $product->meta_title ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
            <div class="lg:col-span-2">
                <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta Description</label>
                <textarea id="meta_description" name="meta_description" rows="3"
                          class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">{{ old('meta_description', $product->meta_description ?? '') }}</textarea>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Product Images</label>
            <input type="file" name="images[]" multiple accept="image/*"
                   class="mt-2 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-churrasco-50 file:text-churrasco-600 hover:file:bg-churrasco-100">
            @if(!empty($product?->images))
                <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($product->images as $image)
                        <img src="{{ Storage::disk('public')->url($image) }}" alt="Product image" class="w-full h-32 object-cover rounded-xl border border-gray-200">
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="flex items-center justify-between">
        <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors duration-200">
            Cancel
        </a>
        <button type="submit" class="inline-flex items-center px-6 py-3 bg-churrasco-600 text-white rounded-xl hover:bg-churrasco-700 transition-colors duration-200">
            {{ $submitLabel }}
        </button>
    </div>
</form>

