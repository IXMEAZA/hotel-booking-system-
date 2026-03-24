<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Room Types
                </h2>
                <p class="text-sm text-gray-500">Manage your hotel room categories and pricing</p>
            </div>

            @if(auth()->user()->role === 'admin')
                <a href="{{ route('room-types.create') }}"
                   class="inline-flex items-center justify-center px-6 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md transition-all w-full sm:w-auto">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Room Type
                </a>
            @endif
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

            {{-- Grid Layout --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 items-stretch">
                @forelse($roomTypes as $rt)
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300 group flex flex-col h-full">

                        {{-- Image Section (Fixed Aspect Ratio) --}}
                        <div class="relative aspect-[16/9] bg-gray-200 overflow-hidden shrink-0">
                            <img src="{{ $rt->image ? asset('storage/'.$rt->image) : 'https://placehold.co/600x400?text=No+Image' }}"
                                 class="w-full h-full object-cover object-center block transform group-hover:scale-105 transition-transform duration-500"
                                 alt="{{ $rt->name }}" loading="lazy">

                            {{-- Price Badge --}}
                            <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full shadow-sm border border-gray-100">
                                <span class="text-sm font-bold text-gray-900">${{ number_format($rt->base_price_per_night, 0) }}</span>
                                <span class="text-xs text-gray-500">/ night</span>
                            </div>
                        </div>

                        {{-- Content Section --}}
                        <div class="p-6 flex-1 flex flex-col text-center items-center">

                            {{-- Title --}}
                            <h3 class="font-bold text-xl text-gray-800 group-hover:text-indigo-600 transition-colors mb-2">
                                {{ $rt->name }}
                            </h3>

                            <p class="text-gray-500 text-sm mb-4 line-clamp-3">
                                {{ $rt->description ?? 'No description provided.' }}
                            </p>

                            {{-- Specs --}}
                            <div class="flex items-center justify-center gap-4 text-sm text-gray-600 w-full mt-auto">
                                <div class="flex items-center gap-1.5 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    <span class="font-medium">{{ $rt->capacity_adults }} Adults</span>
                                </div>
                                <div class="flex items-center gap-1.5 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    <span class="font-medium">{{ $rt->rooms_count ?? 0 }} Rooms</span>
                                </div>
                            </div>
                        </div>

                        {{-- Action Footer --}}
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between shrink-0">
                            <a href="{{ route('room-types.show', $rt) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                                View Details
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>

                            @if(auth()->user()->role === 'admin')
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('room-types.edit', $rt) }}"
                                       class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-white rounded-full transition-all shadow-sm border border-transparent hover:border-gray-200"
                                       title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>

                                    <form method="POST" action="{{ route('room-types.destroy', $rt) }}" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-white rounded-full transition-all shadow-sm border border-transparent hover:border-gray-200"
                                                title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-50 rounded-full mb-4">
                            <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">No Room Types Found</h3>
                        <p class="mt-1 text-gray-500">Get started by adding a new room category.</p>
                        @if(auth()->user()->role === 'admin')
                            <div class="mt-6">
                                <a href="{{ route('room-types.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    Add New Type
                                </a>
                            </div>
                        @endif
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $roomTypes->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
