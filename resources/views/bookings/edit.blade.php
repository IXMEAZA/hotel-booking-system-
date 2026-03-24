<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Edit Booking <span class="text-gray-400 font-medium">#{{ $booking->booking_code }}</span>
                </h2>
                <p class="text-sm text-gray-500">Modify dates, rooms, or guest details</p>
            </div>
            
            <a href="{{ route('bookings.show', $booking) }}"
               class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                ← Back to Details
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Step 1: Check Availability --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 text-xs font-bold">1</span>
                        Check Availability
                    </h3>
                </div>
                
                <div class="p-6">
                    <form method="GET" action="{{ route('bookings.edit', $booking) }}" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                        
                        {{-- Check-in --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Check-in Date</label>
                            <input type="date" name="check_in_date" value="{{ old('check_in_date', $checkIn) }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors cursor-pointer" required>
                            @error('check_in_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Check-out --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Check-out Date</label>
                            <input type="date" name="check_out_date" value="{{ old('check_out_date', $checkOut) }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors cursor-pointer" required>
                            @error('check_out_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Room Type --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Room Type</label>
                            <select name="room_type_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors cursor-pointer" required>
                                <option value="">-- Select --</option>
                                @foreach($roomTypes as $type)
                                    <option value="{{ $type->id }}" @selected((string)old('room_type_id', $roomTypeId) === (string)$type->id)>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('room_type_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Search Button --}}
                        <div>
                            <button type="submit" class="w-full px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md transition-all flex justify-center items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                Search Rooms
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Step 2: Update Booking --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 text-xs font-bold">2</span>
                        Select Room(s) & Update
                    </h3>
                </div>

                <div class="p-6">
                    @if ($availableRooms->isEmpty())
                        <div class="rounded-lg bg-yellow-50 border border-yellow-200 p-4 flex items-start gap-3">
                            <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <div>
                                <h3 class="text-sm font-medium text-yellow-800">No rooms available</h3>
                                <div class="mt-1 text-sm text-yellow-700">Please try different dates or room type in Step 1.</div>
                            </div>
                        </div>
                    @else
                        @if ($errors->has('room_ids'))
                            <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div class="text-sm text-red-700">{{ $errors->first('room_ids') }}</div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('bookings.update', $booking) }}" class="space-y-6">
                            @csrf
                            @method('PATCH')

                            {{-- Hidden Fields --}}
                            <input type="hidden" name="check_in_date" value="{{ old('check_in_date', $checkIn) }}">
                            <input type="hidden" name="check_out_date" value="{{ old('check_out_date', $checkOut) }}">
                            <input type="hidden" name="room_type_id" value="{{ old('room_type_id', $roomTypeId) }}">

                            {{-- Guest Details Grid --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Adults</label>
                                    <input type="number" name="adults_count" min="1" max="20"
                                           value="{{ old('adults_count', $booking->adults_count) }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors" required>
                                    @error('adults_count') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Children</label>
                                    <input type="number" name="children_count" min="0" max="20"
                                           value="{{ old('children_count', $booking->children_count) }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors">
                                    @error('children_count') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                    <textarea name="notes" rows="2" placeholder="Any special requests?"
                                              class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors">{{ old('notes', $booking->notes) }}</textarea>
                                    @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            {{-- Room Selection Table --}}
                            <div class="border rounded-lg overflow-hidden border-gray-200">
                                <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                                    <h4 class="text-sm font-semibold text-gray-700">Available Rooms</h4>
                                </div>
                                <table class="w-full text-left text-sm text-gray-600">
                                    <thead class="bg-gray-50 text-xs uppercase font-medium text-gray-500">
                                        <tr>
                                            <th class="px-4 py-3 w-16 text-center">Select</th>
                                            <th class="px-4 py-3">Room #</th>
                                            <th class="px-4 py-3">Type</th>
                                            <th class="px-4 py-3 text-right">Price / Night</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($availableRooms as $room)
                                            <tr class="hover:bg-gray-50 cursor-pointer" onclick="document.getElementById('room_{{ $room->id }}').click()">
                                                <td class="px-4 py-3 text-center">
                                                    <input type="checkbox" name="room_ids[]" id="room_{{ $room->id }}"
                                                           value="{{ $room->id }}"
                                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-4 h-4 cursor-pointer"
                                                           {{ in_array($room->id, old('room_ids', $selectedRoomIds)) ? 'checked' : '' }}
                                                           onclick="event.stopPropagation()">
                                                </td>
                                                <td class="px-4 py-3 font-medium text-gray-900">{{ $room->room_number }}</td>
                                                <td class="px-4 py-3">{{ $room->roomType?->name }}</td>
                                                <td class="px-4 py-3 text-right font-medium text-gray-900">${{ number_format($room->roomType?->base_price_per_night ?? 0, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @error('room_ids') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                            {{-- Actions --}}
                            <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                                <button type="submit"
                                        class="px-6 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-md transition-all">
                                    Update Booking
                                </button>

                                <a href="{{ route('bookings.show', $booking) }}"
                                   class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>