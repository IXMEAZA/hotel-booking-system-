<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('bookings.index') }}" class="p-2 rounded-full bg-white border border-gray-200 text-gray-500 hover:text-indigo-600 hover:border-indigo-200 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                            Booking #{{ $booking->booking_code }}
                        </h2>
                        @php
                            $statusClasses = match($booking->status) {
                                'confirmed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                                'checked_in' => 'bg-blue-100 text-blue-700 border-blue-200',
                                'checked_out' => 'bg-gray-100 text-gray-600 border-gray-200',
                                default => 'bg-gray-100 text-gray-600 border-gray-200',
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $statusClasses }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500">Created {{ $booking->created_at->format('M d, Y \a\t h:i A') }}</p>
                </div>
            </div>

            <div class="flex gap-3">
                {{-- Invoice Button --}}
                <a href="{{ route('bookings.invoice.download', $booking) }}"
                   class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Invoice PDF
                </a>

                @if($booking->status === 'pending')
                    <a href="{{ route('bookings.edit', $booking) }}"
                       class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Edit Booking
                    </a>
                @endif
            </div>
        </div>
        @if(auth()->user()->role === 'admin')
    @if(in_array($booking->status, ['confirmed','pending']))
        <form method="POST" action="{{ route('bookings.checkin', $booking) }}">
            @csrf
            <button type="submit"
                class="inline-flex items-center justify-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 shadow-md transition-all">
                Check-in
            </button>
        </form>
    @endif

    @if($booking->status === 'checked_in')
        <form method="POST" action="{{ route('bookings.checkout', $booking) }}">
            @csrf
            <button type="submit"
                class="inline-flex items-center justify-center px-4 py-2 bg-gray-700 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 shadow-md transition-all">
                Check-out
            </button>
        </form>
    @endif
@endif

    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg shadow-sm flex items-center justify-between">
                    <div class="flex items-center text-green-800">
                        <svg class="w-6 h-6 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Left Column: Booking Info --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    {{-- Guest & Stay Details --}}
                    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Guest & Stay Information
                            </h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Guest --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Guest Name</label>
                                <div class="font-medium text-gray-900">{{ $booking->guest_name ?? $booking->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $booking->guest_phone ?? $booking->user->email }}</div>
                            </div>

                            {{-- Guests Count --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Guests</label>
                                <div class="font-medium text-gray-900">
                                    {{ $booking->adults_count }} Adults
                                    @if($booking->children_count > 0), {{ $booking->children_count }} Children @endif
                                </div>
                            </div>

                            {{-- Dates --}}
                            <div class="md:col-span-2 bg-indigo-50 rounded-lg p-4 flex flex-col sm:flex-row items-center justify-between gap-4 border border-indigo-100">
                                <div class="text-center sm:text-left">
                                    <label class="block text-xs font-semibold text-indigo-400 uppercase tracking-wider mb-1">Check-in</label>
                                    <div class="font-bold text-lg text-indigo-900">{{ $booking->check_in_date->format('D, M d, Y') }}</div>
                                </div>
                                
                                <div class="flex flex-col items-center">
                                    <div class="w-24 h-px bg-indigo-200 relative top-3"></div>
                                    <div class="px-3 py-1 bg-white rounded-full text-xs font-bold text-indigo-600 shadow-sm border border-indigo-100 z-10">
                                        {{ $booking->check_in_date->diffInDays($booking->check_out_date) }} Nights
                                    </div>
                                </div>

                                <div class="text-center sm:text-right">
                                    <label class="block text-xs font-semibold text-indigo-400 uppercase tracking-wider mb-1">Check-out</label>
                                    <div class="font-bold text-lg text-indigo-900">{{ $booking->check_out_date->format('D, M d, Y') }}</div>
                                </div>
                            </div>

                            {{-- Notes --}}
                            @if($booking->notes)
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Notes</label>
                                    <div class="bg-yellow-50 text-yellow-800 p-3 rounded-lg text-sm border border-yellow-100">
                                        {{ $booking->notes }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Rooms List --}}
                    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                Room Details
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50 border-b border-gray-100">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Room #</th>
                                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-right">Price / Night</th>
                                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($booking->rooms as $room)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 font-medium text-gray-900">{{ $room->room_number }}</td>
                                            <td class="px-6 py-4 text-gray-600">{{ $room->roomType->name }}</td>
                                            <td class="px-6 py-4 text-gray-600 text-right">${{ number_format((float)$room->pivot->price_per_night, 2) }}</td>
                                            <td class="px-6 py-4 font-medium text-gray-900 text-right">${{ number_format((float)$room->pivot->line_total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                {{-- Right Column: Payment Info --}}
                <div class="lg:col-span-1 space-y-8">
                    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Payment Summary
                            </h3>
                        </div>
                        <div class="p-6 space-y-4">
                            
                            {{-- Calculations --}}
                            @php
                                $roomTotal = $booking->rooms->sum(fn($r) => (float) $r->pivot->line_total);
                                $discount = (float) $booking->discount_amount;
                                $grandTotal = $roomTotal - $discount;
                                $paid = (float) $booking->paid_amount;
                                $remaining = $grandTotal - $paid;
                            @endphp

                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Subtotal</span>
                                <span>${{ number_format($roomTotal, 2) }}</span>
                            </div>

                            @if($discount > 0)
                                <div class="flex justify-between text-sm text-green-600">
                                    <span>Discount</span>
                                    <span>-${{ number_format($discount, 2) }}</span>
                                </div>
                            @endif

                            <div class="border-t border-gray-100 my-2 pt-2"></div>

                            <div class="flex justify-between text-lg font-bold text-gray-900">
                                <span>Grand Total</span>
                                <span>${{ number_format($grandTotal, 2) }}</span>
                            </div>

                            <div class="flex justify-between text-sm text-gray-500 mt-2">
                                <span>Paid</span>
                                <span class="font-medium text-gray-900">${{ number_format($paid, 2) }}</span>
                            </div>

                            <div class="flex justify-between text-sm font-medium {{ $remaining > 0 ? 'text-red-600' : 'text-green-600' }}">
                                <span>Remaining Due</span>
                                <span>${{ number_format($remaining, 2) }}</span>
                            </div>

                            {{-- Payment Method Badge --}}
                            <div class="mt-6 pt-4 border-t border-gray-100">
                                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Payment Method</span>
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    {{ ucfirst($booking->payment_method ?? 'Cash') }}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
        
    </div>
</x-app-layout>