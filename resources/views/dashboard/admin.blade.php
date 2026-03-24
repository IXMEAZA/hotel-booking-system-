<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">Overview</h2>
                <p class="text-sm text-gray-500 mt-1">Welcome back, here's what's happening today.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- 1. Stats Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                {{-- Rooms Card --}}
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Rooms</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $roomsCount }}</h3>
                        <div class="flex items-center mt-2 text-xs">
                            <span class="text-green-600 bg-green-50 px-2 py-0.5 rounded-full font-medium">
                                {{ $activeRoomsCount }} Active
                            </span>
                        </div>
                    </div>
                    <div class="p-3 bg-indigo-50 rounded-lg text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                </div>

                {{-- Bookings Card --}}
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Monthly Bookings</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $bookingsThisMonth }}</h3>
                        <p class="text-xs text-gray-400 mt-2">New reservations this month</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-lg text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>

                {{-- Available Rooms --}}
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Available Now</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $availableRoomsCount }}</h3>
                        <p class="text-xs text-gray-400 mt-2">Ready for check-in</p>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-lg text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                {{-- Occupied/Maintenance --}}
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Occupied / Maint.</p>
                        <div class="flex items-baseline gap-2 mt-2">
                            <h3 class="text-3xl font-bold text-gray-800">{{ $occupiedRoomsCount }}</h3>
                            <span class="text-sm text-gray-400">/ {{ $maintenanceRoomsCount }}</span>
                        </div>
                    </div>
                    <div class="p-3 bg-orange-50 rounded-lg text-orange-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                </div>
            </div>

            {{-- 2. Status Summary & Latest Bookings --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Booking Status (Small Column) --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-fit">
                    <h3 class="font-bold text-gray-800 mb-4">Booking Status</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-amber-50 rounded-lg border border-amber-100">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                                <span class="text-sm font-medium text-amber-900">Pending</span>
                            </div>
                            <span class="text-lg font-bold text-amber-700">{{ $pendingBookings }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-100">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                <span class="text-sm font-medium text-green-900">Confirmed</span>
                            </div>
                            <span class="text-lg font-bold text-green-700">{{ $confirmedBookings }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-100">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                <span class="text-sm font-medium text-red-900">Cancelled</span>
                            </div>
                            <span class="text-lg font-bold text-red-700">{{ $cancelledBookings }}</span>
                        </div>
                    </div>
                </div>

                {{-- Latest Bookings Table (Wide Column) --}}
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="font-bold text-gray-800">Latest Bookings</h3>
                        <a href="{{ route('bookings.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                            View all 
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                                <tr>
                                    <th class="px-6 py-4">Customer</th>
                                    <th class="px-6 py-4">Check-in</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                    <th class="px-6 py-4 text-right">Total</th> {{-- عمود السعر الجديد --}}
                                    <th class="px-6 py-4"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($latestBookings as $b)
                                    @php
                                        // حساب المجموع يدوياً إذا لم يكن مخزناً
                                        $total = $b->rooms->sum(fn($room) => $room->pivot->line_total ?? 0);
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="font-medium text-gray-900">{{ $b->user?->name ?? 'Guest' }}</span>
                                                <span class="text-xs text-gray-400 font-mono">{{ $b->booking_code }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $b->check_in_date->format('M d') }} 
                                            <span class="text-gray-400 mx-1">➜</span> 
                                            {{ $b->check_out_date->format('M d') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $color = match($b->status) {
                                                    'confirmed' => 'bg-green-100 text-green-700',
                                                    'pending' => 'bg-amber-100 text-amber-700',
                                                    'cancelled' => 'bg-red-100 text-red-700',
                                                    default => 'bg-gray-100 text-gray-700',
                                                };
                                            @endphp
                                            <span class="px-2 py-1 rounded-full text-xs font-bold {{ $color }}">
                                                {{ ucfirst($b->status) }}
                                            </span>
                                        </td>
                                        {{-- عرض السعر مع الدولار --}}
                                        <td class="px-6 py-4 text-right font-medium text-gray-900">
                                            ${{ number_format($total, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('bookings.show', $b) }}" class="text-gray-400 hover:text-indigo-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                            No bookings found yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>