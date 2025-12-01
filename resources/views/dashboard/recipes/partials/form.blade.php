@php
    use Illuminate\Support\Facades\Storage;

    $ingredientsValue = is_array(old('ingredients')) ? implode("\n", old('ingredients')) : (old('ingredients') ?? implode("\n", $recipe->ingredients ?? []));
    $instructionsValue = is_array(old('instructions')) ? implode("\n", old('instructions')) : (old('instructions') ?? implode("\n", $recipe->instructions ?? []));
    $tipsValue = is_array(old('tips')) ? implode("\n", old('tips')) : (old('tips') ?? implode("\n", $recipe->tips ?? []));
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
                <input type="text" id="title" name="title" value="{{ old('title', $recipe->title ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500" required>
            </div>
            <div>
                <label for="establishment_id" class="block text-sm font-medium text-gray-700">Establishment (optional)</label>
                <select id="establishment_id" name="establishment_id"
                        class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                    <option value="">Shared recipe</option>
                    @foreach($establishments as $establishmentId => $establishmentName)
                        <option value="{{ $establishmentId }}" @selected((int) old('establishment_id', $recipe->establishment_id ?? '') === (int) $establishmentId)>
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
                        class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                    @foreach($categories as $category)
                        <option value="{{ $category }}" @selected(old('category', $recipe->category ?? '') === $category)>
                            {{ ucfirst($category) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="difficulty" class="block text-sm font-medium text-gray-700">Difficulty</label>
                <select id="difficulty" name="difficulty"
                        class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                    @foreach($difficulties as $difficulty)
                        <option value="{{ $difficulty }}" @selected(old('difficulty', $recipe->difficulty ?? '') === $difficulty)>
                            {{ ucfirst($difficulty) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="servings" class="block text-sm font-medium text-gray-700">Servings</label>
                <input type="number" id="servings" name="servings" value="{{ old('servings', $recipe->servings ?? 4) }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500" required>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div>
                <label for="prep_time_minutes" class="block text-sm font-medium text-gray-700">Prep time (min)</label>
                <input type="number" id="prep_time_minutes" name="prep_time_minutes"
                       value="{{ old('prep_time_minutes', $recipe->prep_time_minutes ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
            <div>
                <label for="cook_time_minutes" class="block text-sm font-medium text-gray-700">Cook time (min)</label>
                <input type="number" id="cook_time_minutes" name="cook_time_minutes"
                       value="{{ old('cook_time_minutes', $recipe->cook_time_minutes ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
            <div>
                <label for="rest_time_minutes" class="block text-sm font-medium text-gray-700">Rest time (min)</label>
                <input type="number" id="rest_time_minutes" name="rest_time_minutes"
                       value="{{ old('rest_time_minutes', $recipe->rest_time_minutes ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
        </div>

        <div>
            <label for="summary" class="block text-sm font-medium text-gray-700">Summary</label>
            <textarea id="summary" name="summary" rows="2"
                      class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">{{ old('summary', $recipe->summary ?? '') }}</textarea>
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Full description</label>
            <textarea id="description" name="description" rows="4"
                      class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">{{ old('description', $recipe->description ?? '') }}</textarea>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label for="ingredients" class="block text-sm font-medium text-gray-700">Ingredients</label>
                <textarea id="ingredients" name="ingredients" rows="6" placeholder="One ingredient per line"
                          class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">{{ $ingredientsValue }}</textarea>
            </div>
            <div>
                <label for="instructions" class="block text-sm font-medium text-gray-700">Instructions</label>
                <textarea id="instructions" name="instructions" rows="6" placeholder="One step per line"
                          class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">{{ $instructionsValue }}</textarea>
            </div>
        </div>

        <div>
            <label for="tips" class="block text-sm font-medium text-gray-700">Tips (optional)</label>
            <textarea id="tips" name="tips" rows="3" placeholder="One tip per line"
                      class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">{{ $tipsValue }}</textarea>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Recipe images</label>
                <input type="file" name="images[]" multiple accept="image/*"
                       class="mt-2 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-churrasco-50 file:text-churrasco-600 hover:file:bg-churrasco-100">
                @if(!empty($recipe?->images))
                    <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($recipe->images as $image)
                            <img src="{{ Storage::disk('public')->url($image) }}" alt="Recipe image" class="w-full h-32 object-cover rounded-xl border border-gray-200">
                        @endforeach
                    </div>
                @endif
            </div>
            <div>
                <label for="video_url" class="block text-sm font-medium text-gray-700">Video URL</label>
                <input type="url" id="video_url" name="video_url" value="{{ old('video_url', $recipe->video_url ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500" placeholder="https://">
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $recipe->is_featured ?? false))
                       class="rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                <span class="ml-2 text-sm text-gray-700">Feature this recipe</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $recipe->is_published ?? false))
                       class="rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                <span class="ml-2 text-sm text-gray-700">Publish immediately</span>
            </label>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label for="published_at" class="block text-sm font-medium text-gray-700">Publish at</label>
                <input type="datetime-local" id="published_at" name="published_at"
                       value="{{ old('published_at', optional($recipe->published_at ?? null)?->format('Y-m-d\TH:i')) }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
            <div>
                <label for="favorite_count" class="block text-sm font-medium text-gray-700">Favorite count</label>
                <input type="number" id="favorite_count" name="favorite_count" value="{{ old('favorite_count', $recipe->favorite_count ?? 0) }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
        </div>

        <div>
            <label for="nutrition_facts" class="block text-sm font-medium text-gray-700">Nutrition facts (JSON)</label>
            <textarea id="nutrition_facts" name="nutrition_facts" rows="3"
                      class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500"
                      placeholder='{"calories": "250kcal"}'>{{ is_array(old('nutrition_facts')) ? json_encode(old('nutrition_facts')) : (old('nutrition_facts') ?? ($recipe->nutrition_facts ? json_encode($recipe->nutrition_facts) : '')) }}</textarea>
        </div>
    </div>

    <div class="flex items-center justify-between">
        <a href="{{ route('recipes.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors duration-200">
            Cancel
        </a>
        <button type="submit" class="inline-flex items-center px-6 py-3 bg-churrasco-600 text-white rounded-xl hover:bg-churrasco-700 transition-colors duration-200">
            {{ $submitLabel }}
        </button>
    </div>
</form>












