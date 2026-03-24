<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Rooms Management
                </h2>
                <p class="text-sm text-gray-500">Manage hotel rooms availability</p>
            </div>

            <a href="{{ route('rooms.create') }}"
               class="inline-flex items-center justify-center px-6 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md transition-all w-full sm:w-auto">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Room
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alerts --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg shadow-sm flex items-center justify-between">
                    <div class="flex items-center text-green-800">
                        <svg class="w-6 h-6 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-green-600 hover:text-green-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100">
                
                {{-- Header & Filter --}}
                <div class="p-6 border-b border-gray-100 bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    
                    {{-- Title --}}
                    <div class="flex items-center gap-3">
                        <div class="bg-indigo-50 p-2.5 rounded-xl text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">All Rooms</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $rooms->total() }} rooms
                            </span>
                        </div>
                    </div>

 {{-- ✅ Fix double arrows (force remove native/background arrow) --}}
<style>
    select.no-arrow {
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        appearance: none !important;
        background-image: none !important;
        background-repeat: no-repeat !important;
    }
</style>

{{-- Filters --}}
<div class="w-full sm:w-auto">
    <form action="{{ route('rooms.index') }}" method="GET"
          class="grid grid-cols-1 sm:grid-cols-3 gap-3">

        {{-- Status --}}
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
            </div>

            <select name="status" onchange="this.form.submit()"
                    class="block w-full pl-11 pr-3 py-2.5 text-sm border-gray-300
                           focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm
                           bg-white hover:border-indigo-300 cursor-pointer transition-all">
                <option value="">Status (All)</option>
                <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                <option value="occupied" {{ request('status') === 'occupied' ? 'selected' : '' }}>Occupied</option>
                <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>
        </div>

        {{-- Room Type --}}
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 10h18M3 6h18M3 14h18M3 18h18"/>
                </svg>
            </div>

            <select name="room_type_id" onchange="this.form.submit()"
                    class="block w-full pl-11 pr-3 py-2.5 text-sm border-gray-300
                           focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm
                           bg-white hover:border-indigo-300 cursor-pointer transition-all">
                <option value="">Room Type (All)</option>
                @foreach($roomTypes as $rt)
                    <option value="{{ $rt->id }}" {{ (string)request('room_type_id') === (string)$rt->id ? 'selected' : '' }}>
                        {{ $rt->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Floor --}}
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 3v18m9-9H3"/>
                </svg>
            </div>

            <select name="floor" onchange="this.form.submit()"
                    class="block w-full pl-11 pr-3 py-2.5 text-sm border-gray-300
                           focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm
                           bg-white hover:border-indigo-300 cursor-pointer transition-all">
                <option value="">Floor (All)</option>
                @foreach($floors as $f)
                    <option value="{{ $f }}" {{ (string)request('floor') === (string)$f ? 'selected' : '' }}>
                        Floor {{ $f }}
                    </option>
                @endforeach
            </select>
        </div>

    </form>
</div>




    {{-- Reset button --}}
    @if(request()->filled('status') || request()->filled('room_type_id') || request()->filled('floor'))
        <div class="mt-2">
            <a href="{{ route('rooms.index') }}"
               class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700">
                Reset Filters
            </a>
        </div>
    @endif
</div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                <th class="py-4 px-6">Room Info</th>
                                <th class="py-4 px-6">Floor</th>
                                <th class="py-4 px-6 text-center">Status</th>
                                <th class="py-4 px-6 text-center">Active</th>
                                <th class="py-4 px-6 text-right">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($rooms as $room)
                                @php
                                    // توحيد الحالة لحروف صغيرة لضمان عمل الألوان
                                    $statusKey = strtolower($room->status);

                                    $statusClasses = match($statusKey) {
                                        'available' => 'bg-green-100 text-green-700 border border-green-200', // أخضر واضح
                                        'occupied' => 'bg-red-100 text-red-700 border border-red-200',     // أحمر واضح
                                        'maintenance' => 'bg-yellow-100 text-yellow-800 border border-yellow-200', // أصفر
                                        default => 'bg-gray-100 text-gray-600 border border-gray-200',
                                    };
                                    
                                    $dotColor = match($statusKey) {
                                        'available' => 'text-green-600',
                                        'occupied' => 'text-red-600',
                                        'maintenance' => 'text-yellow-600',
                                        default => 'text-gray-400',
                                    };
                                @endphp

                                <tr class="hover:bg-gray-50/80 transition-colors duration-200 group">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-gray-100 rounded-lg text-gray-600 font-mono font-bold text-lg border border-gray-200">
                                                {{ $room->room_number }}
                                            </div>
                                            <div>
                                                <span class="block text-sm font-bold text-gray-900">{{ $room->roomType?->name }}</span>
                                                <span class="text-xs text-gray-500">
                                                    ${{ number_format($room->roomType?->base_price_per_night, 2) }} / night
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="py-4 px-6">
                                        <span class="text-sm text-gray-600 font-medium bg-gray-50 px-2 py-1 rounded">Floor {{ $room->floor }}</span>
                                    </td>
                                    
                                    <td class="py-4 px-6 text-center">
                                        <span class="inline-flex items-center gap-x-1.5 rounded-full px-3 py-1 text-xs font-bold {{ $statusClasses }}">
                                            <svg class="h-1.5 w-1.5 {{ $dotColor }} fill-current" viewBox="0 0 6 6" aria-hidden="true">
                                              <circle cx="3" cy="3" r="3" />
                                            </svg>
                                            {{ ucfirst($room->status) }}
                                        </span>
                                    </td>

                                    <td class="py-4 px-6 text-center">
                                        @if($room->is_active)
                                            <div class="flex justify-center">
                                                <span class="inline-flex items-center justify-center w-8 h-8 bg-green-50 rounded-full text-green-600 border border-green-100" title="Active">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                </span>
                                            </div>
                                        @else
                                            <div class="flex justify-center">
                                                <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-50 rounded-full text-gray-400 border border-gray-200" title="Inactive">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </span>
                                            </div>
                                        @endif
                                    </td>

                                    <td class="py-4 px-6 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- Edit --}}
                                            <a href="{{ route('rooms.edit', $room) }}"
                                               class="text-gray-500 hover:text-indigo-600 bg-white hover:bg-indigo-50 border border-gray-200 hover:border-indigo-200 p-2 rounded-lg transition-all shadow-sm"
                                               title="Edit Room">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>

                                            {{-- Delete --}}
                                            <form action="{{ route('rooms.destroy', $room) }}" method="POST"
                                                  onsubmit="return confirm('Are you sure you want to delete this room?');"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-gray-500 hover:text-red-600 bg-white hover:bg-red-50 border border-gray-200 hover:border-red-200 p-2 rounded-lg transition-all shadow-sm"
                                                        title="Delete Room">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <div class="bg-gray-100 p-4 rounded-full mb-3">
                                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            </div>
                                            <p class="text-lg font-medium text-gray-500">No rooms found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($rooms->hasPages())
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                        {{ $rooms->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>