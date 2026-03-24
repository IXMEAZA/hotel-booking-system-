<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Room Type
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <form method="POST"
                          action="{{ route('room-types.store') }}"
                          class="space-y-4"
                          enctype="multipart/form-data">
                        @csrf

                        <div>
                            <label class="block font-medium">Name</label>
                            <input name="name" value="{{ old('name') }}"
                                   class="mt-1 w-full rounded border-gray-300" required>
                            @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block font-medium">Capacity (Adults)</label>
                            <input type="number" name="capacity_adults" value="{{ old('capacity_adults', 1) }}"
                                   class="mt-1 w-full rounded border-gray-300" min="1" required>
                            @error('capacity_adults') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block font-medium">Base Price / Night</label>
                            <input type="number" step="0.01" name="base_price_per_night"
                                   value="{{ old('base_price_per_night') }}"
                                   class="mt-1 w-full rounded border-gray-300" min="0" required>
                            @error('base_price_per_night') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block font-medium">Description</label>
                            <textarea name="description" class="mt-1 w-full rounded border-gray-300" rows="4">{{ old('description') }}</textarea>
                            @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- ✅ Image Upload --}}
                        <div>
                            <label class="block font-medium">Image</label>

                            <input type="file"
                                   name="image"
                                   accept="image/*"
                                   class="mt-1 w-full rounded border-gray-300"
                                   onchange="previewRoomTypeImage(event)">

                            @error('image') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

                            <div class="mt-3">
                                <img id="roomTypePreview"
                                     src=""
                                     class="hidden w-48 h-32 object-cover rounded border"
                                     alt="Preview">
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" id="is_active" checked>
                            <label for="is_active">Active</label>
                        </div>

                        <div class="flex gap-2">
                            <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                Save
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
                            if (!file) {
                                img.src = '';
                                img.classList.add('hidden');
                                return;
                            }
                            img.src = URL.createObjectURL(file);
                            img.classList.remove('hidden');
                        }
                    </script>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
