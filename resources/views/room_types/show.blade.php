<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Room Type Details
            </h2>

            <a href="{{ route('room-types.index') }}"
               class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-xl shadow overflow-hidden">
                {{-- Image --}}
                <div class="h-64 bg-gray-100 overflow-hidden">
                    <img
                        src="{{ $roomType->image ? asset('storage/'.$roomType->image) : 'https://via.placeholder.com/1200x600' }}"
                        class="w-full h-full object-cover"
                        alt="{{ $roomType->name }}"
                    >
                </div>

                <div class="p-6 space-y-4">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $roomType->name }}</h3>
                            <p class="text-gray-600 mt-2">
                                {{ $roomType->description ?? 'No description.' }}
                            </p>
                        </div>

                        <div class="text-right">
                            <div class="text-sm text-gray-500">Status</div>
                            @if($roomType->is_active)
                                <span class="inline-flex px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-medium">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex px-3 py-1 rounded-full bg-gray-200 text-gray-700 text-sm font-medium">
                                    Inactive
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 rounded-lg border bg-gray-50">
                            <div class="text-sm text-gray-500">Capacity (Adults)</div>
                            <div class="text-lg font-semibold">{{ $roomType->capacity_adults }}</div>
                        </div>

                        <div class="p-4 rounded-lg border bg-gray-50">
                            <div class="text-sm text-gray-500">Base Price / Night</div>
                            <div class="text-lg font-semibold">{{ number_format($roomType->base_price_per_night, 2) }}</div>
                        </div>
                    </div>

                    {{-- Admin actions --}}
                    @if(auth()->user()->role === 'admin')
                        <div class="flex gap-2 pt-2">
                            <a href="{{ route('room-types.edit', $roomType) }}"
                               class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                Edit
                            </a>

                            <form method="POST" action="{{ route('room-types.destroy', $roomType) }}"
                                  onsubmit="return confirm('Delete this room type?')">
                                @csrf
                                @method('DELETE')
                                <button class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
