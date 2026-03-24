<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Room Type
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <form method="POST"
                          action="{{ route('room-types.update', $roomType) }}"
                          class="space-y-4"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="block font-medium">Name</label>
                            <input name="name" value="{{ old('name', $roomType->name) }}"
                                   class="mt-1 w-full rounded border-gray-300" required>
                            @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block font-medium">Capacity (Adults)</label>
                            <input type="number" name="capacity_adults"
                                   value="{{ old('capacity_adults', $roomType->capacity_adults) }}"
                                   class="mt-1 w-full rounded border-gray-300" min="1" required>
                            @error('capacity_adults') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block font-medium">Base Price / Night</label>
                            <input type="number" step="0.01" name="base_price_per_night"
                                   value="{{ old('base_price_per_night', $roomType->base_price_per_night) }}"
                                   class="mt-1 w-full rounded border-gray-300" min="0" required>
                            @error('base_price_per_night') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block font-medium">Description</label>
                            <textarea name="description" class="mt-1 w-full rounded border-gray-300" rows="4">{{ old('description', $roomType->description) }}</textarea>
                            @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- ✅ Current Image + Upload New --}}
                        <div>
                            <label class="block font-medium">Image</label>

                            @if($roomType->image)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600 mb-2">Current image:</p>
                                    <img src="{{ asset('storage/'.$roomType->image) }}"
                                         class="w-48 h-32 object-cover rounded border"
                                         alt="Current image">
                                </div>
                            @endif

                            <input type="file"
                                   name="image"
                                   accept="image/*"
                                   class="mt-3 w-full rounded border-gray-300"
                                   onchange="previewRoomTypeImage(event)">

                            @error('image') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

                            <div class="mt-3">
                                <p class="text-sm text-gray-600 mb-2 hidden" id="newImageLabel">New image preview:</p>
                                <img id="roomTypePreview"
                                     src=""
                                     class="hidden w-48 h-32 object-cover rounded border"
                                     alt="Preview">
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" id="is_active"
                                   {{ old('is_active', $roomType->is_active) ? 'checked' : '' }}>
                            <label for="is_active">Active</label>
                        </div>

                        <div class="flex gap-2">
                            <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                Update
                            </button>
                            <a href="{{ route('room-types.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                                Cancel
                            </a>
                        </div>
                    </form>

                    <script>
                        function previewRoomTypeImage(event) {
                            const file = event.target.files?.[0];
                            const img = document.getElementById('roomTypePreview');
                            const label = document.getElementById('newImageLabel');

                            if (!file) {
                                img.src = '';
                                img.classList.add('hidden');
                                label.classList.add('hidden');
                                return;
                            }

                            img.src = URL.createObjectURL(file);
                            img.classList.remove('hidden');
                            label.classList.remove('hidden');
                        }
                    </script>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
