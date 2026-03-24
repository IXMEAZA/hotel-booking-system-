<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Room</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('rooms.store') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block font-medium">Room Number</label>
                            <input name="room_number" value="{{ old('room_number') }}"
                                   class="mt-1 w-full rounded border-gray-300" required>
                            @error('room_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block font-medium">Room Type</label>
                            <select name="room_type_id" class="mt-1 w-full rounded border-gray-300" required>
                                <option value="">-- Select --</option>
                                @foreach($roomTypes as $type)
                                    <option value="{{ $type->id }}" @selected(old('room_type_id') == $type->id)>
                                        {{ $type->name }} (Cap: {{ $type->capacity_adults }})
                                    </option>
                                @endforeach
                            </select>
                            @error('room_type_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block font-medium">Floor</label>
                            <input type="number" name="floor" value="{{ old('floor') }}"
                                   class="mt-1 w-full rounded border-gray-300">
                            @error('floor') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block font-medium">Status</label>
                            <select name="status" class="mt-1 w-full rounded border-gray-300" required>
                                @foreach(['available','occupied','maintenance'] as $st)
                                    <option value="{{ $st }}" @selected(old('status','available') === $st)>
                                        {{ ucfirst($st) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" id="is_active" checked>
                            <label for="is_active">Active</label>
                        </div>

                        <div class="flex gap-2">
                            <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                Save
                            </button>
                            <a href="{{ route('rooms.index') }}"
                               class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
