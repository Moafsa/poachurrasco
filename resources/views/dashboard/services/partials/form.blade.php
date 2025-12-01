@php
    use Illuminate\Support\Facades\Storage;

    $addons = collect(old('addons', $service->addons ?? []))->values();
    $serviceHours = collect(old('service_hours', $service->service_hours ?? []))->values();
    $tagsValue = is_array(old('tags')) ? implode(', ', old('tags')) : (isset($service) ? implode(', ', $service->tags ?? []) : '');
@endphp

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-8">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Service name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $service->name ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500" required>
            </div>
            <div>
                <label for="establishment_id" class="block text-sm font-medium text-gray-700">Establishment</label>
                <select id="establishment_id" name="establishment_id"
                        class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                    @foreach($establishments as $establishmentId => $establishmentName)
                        <option value="{{ $establishmentId }}" @selected((int) old('establishment_id', $service->establishment_id ?? '') === (int) $establishmentId)>
                            {{ $establishmentName }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                <select id="category" name="category"
                        class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500" required>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" @selected(old('category', $service->category ?? '') === $category)>
                            {{ ucfirst(str_replace('_', ' ', $category)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                <input type="number" step="0.01" id="price" name="price"
                       value="{{ old('price', $service->price ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500" required>
            </div>
            <div>
                <label for="setup_fee" class="block text-sm font-medium text-gray-700">Setup fee</label>
                <input type="number" step="0.01" id="setup_fee" name="setup_fee"
                       value="{{ old('setup_fee', $service->setup_fee ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div>
                <label for="duration_minutes" class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
                <input type="number" id="duration_minutes" name="duration_minutes"
                       value="{{ old('duration_minutes', $service->duration_minutes ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
            <div>
                <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                <input type="number" id="capacity" name="capacity"
                       value="{{ old('capacity', $service->capacity ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
            <div class="flex items-center space-x-4 mt-6">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="includes_meat" value="1" @checked(old('includes_meat', $service->includes_meat ?? false))
                           class="rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                    <span class="ml-2 text-sm text-gray-700">Includes meat</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="includes_staff" value="1" @checked(old('includes_staff', $service->includes_staff ?? true))
                           class="rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                    <span class="ml-2 text-sm text-gray-700">Includes staff</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="includes_equipment" value="1" @checked(old('includes_equipment', $service->includes_equipment ?? true))
                           class="rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                    <span class="ml-2 text-sm text-gray-700">Includes equipment</span>
                </label>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $service->is_featured ?? false))
                           class="rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                    <span class="ml-2 text-sm text-gray-700">Feature this service</span>
                </label>
            </div>
            <div>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $service->is_active ?? true))
                           class="rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                    <span class="ml-2 text-sm text-gray-700">Service is active</span>
                </label>
            </div>
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" rows="4"
                      class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">{{ old('description', $service->description ?? '') }}</textarea>
        </div>

        <div>
            <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
            <input type="text" id="tags" name="tags" value="{{ $tagsValue }}" placeholder="separate values with comma"
                   class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Service images</label>
            <input type="file" name="images[]" multiple accept="image/*"
                   class="mt-2 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-churrasco-50 file:text-churrasco-600 hover:file:bg-churrasco-100">
            @if(!empty($service?->images))
                <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($service->images as $image)
                        <img src="{{ Storage::disk('public')->url($image) }}" alt="Service image" class="w-full h-32 object-cover rounded-xl border border-gray-200">
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-4">
        <h2 class="text-lg font-semibold text-gray-900">Included add-ons</h2>
        <p class="text-sm text-gray-500">Define optional add-ons with pricing. Leave fields blank if not required.</p>
        <div class="space-y-4">
            @for($index = 0; $index < 3; $index++)
                @php
                    $addon = $addons[$index] ?? ['name' => '', 'price' => ''];
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border border-gray-200 rounded-xl p-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Add-on name</label>
                        <input type="text" name="addons[{{ $index }}][name]" value="{{ $addon['name'] ?? '' }}"
                               class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Price</label>
                        <input type="number" step="0.01" name="addons[{{ $index }}][price]" value="{{ $addon['price'] ?? '' }}"
                               class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-4">
        <h2 class="text-lg font-semibold text-gray-900">Service schedule</h2>
        <p class="text-sm text-gray-500">Map available windows for bookings (e.g. Friday 18:00 - 23:00).</p>
        <div class="space-y-4">
            @for($index = 0; $index < 3; $index++)
                @php
                    $slot = $serviceHours[$index] ?? ['day' => '', 'start' => '', 'end' => ''];
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border border-gray-200 rounded-xl p-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Day</label>
                        <input type="text" name="service_hours[{{ $index }}][day]" value="{{ $slot['day'] ?? '' }}"
                               placeholder="Friday"
                               class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Start</label>
                        <input type="time" name="service_hours[{{ $index }}][start]" value="{{ $slot['start'] ?? '' }}"
                               class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">End</label>
                        <input type="time" name="service_hours[{{ $index }}][end]" value="{{ $slot['end'] ?? '' }}"
                               class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <div class="flex items-center justify-between">
        <a href="{{ route('services.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors duration-200">
            Cancel
        </a>
        <button type="submit" class="inline-flex items-center px-6 py-3 bg-churrasco-600 text-white rounded-xl hover:bg-churrasco-700 transition-colors duration-200">
            {{ $submitLabel }}
        </button>
    </div>
</form>












