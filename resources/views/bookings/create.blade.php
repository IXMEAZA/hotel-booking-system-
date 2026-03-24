<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    New Booking
                </h2>
                <p class="text-sm text-gray-500">Create a reservation for a guest</p>
            </div>
            
            <a href="{{ route('bookings.index') }}"
               class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                ← Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Step 1: Select Dates --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 text-xs font-bold">1</span>
                        Select Dates
                    </h3>
                </div>
                
                <div class="p-6">
                    <form method="GET" action="{{ route('bookings.create') }}" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                        
                        {{-- Check-in --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Check-in Date</label>
                            <div class="relative">
                                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <input type="date" name="check_in_date" value="{{ request('check_in_date') }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors cursor-pointer pl-10" required>
                            </div>
                            @error('check_in_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Check-out --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Check-out Date</label>
                            <div class="relative">
                                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <input type="date" name="check_out_date" value="{{ request('check_out_date') }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors cursor-pointer pl-10" required>
                            </div>
                            @error('check_out_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Search Button --}}
                        <div>
                            <button type="submit" class="w-full px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md transition-all flex justify-center items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                Search Availability
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Step 2: Booking Details --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 text-xs font-bold">2</span>
                        Select Rooms & Confirm
                    </h3>
                </div>

                <div class="p-6">
                    @if (!request()->filled(['check_in_date','check_out_date']))
                        <div class="rounded-lg bg-gray-50 border border-gray-200 p-8 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-100 rounded-full mb-3">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <h3 class="text-sm font-medium text-gray-900">Select dates first</h3>
                            <p class="mt-1 text-sm text-gray-500">Please choose check-in and check-out dates in Step 1 to view availability.</p>
                        </div>
                    @elseif ($availableRooms->isEmpty())
                        <div class="rounded-lg bg-yellow-50 border border-yellow-200 p-4 flex items-start gap-3">
                            <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <div>
                                <h3 class="text-sm font-medium text-yellow-800">No rooms available</h3>
                                <div class="mt-1 text-sm text-yellow-700">All rooms are booked for these dates. Please try a different range.</div>
                            </div>
                        </div>
                    @else
                        
                        @if (session('error'))
                            <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div class="text-sm text-red-700">{{ session('error') }}</div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('bookings.store') }}" class="space-y-8" id="bookingForm">
                            @csrf
                            <input type="hidden" name="check_in_date" id="check_in_date" value="{{ request('check_in_date') }}">
                            <input type="hidden" name="check_out_date" id="check_out_date" value="{{ request('check_out_date') }}">

                            {{-- Guest & Payment Grid --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                {{-- Guest Name --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Guest Name</label>
                                    <div class="relative">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                        <input type="text" name="guest_name" value="{{ old('guest_name') }}" placeholder="Full Name"
                                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors pl-10" required>
                                    </div>
                                    @error('guest_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Guest Phone --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Guest Phone</label>
                                    <div class="relative">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        </div>
                                        <input type="text" name="guest_phone" value="{{ old('guest_phone') }}" placeholder="Phone Number"
                                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors pl-10" required>
                                    </div>
                                    @error('guest_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Payment Method --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                                    <div class="relative">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                        </div>
                                        <select name="payment_method" id="payment_method" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors cursor-pointer pl-10" required>
                                            <option value="cash" {{ old('payment_method','cash') === 'cash' ? 'selected' : '' }}>Cash (Pay Later)</option>
                                            <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>Card (Pay Now)</option>
                                        </select>
                                    </div>
                                    @error('payment_method') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <input type="hidden" name="paid_amount" id="paid_amount" value="{{ old('paid_amount', 0) }}">
                            </div>

                            {{-- Details Grid --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Adults</label>
                                    <div class="relative">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        </div>
                                        <input type="number" name="adults_count" min="1" max="20" value="{{ old('adults_count', 1) }}"
                                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors pl-10" required>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Children</label>
                                    <div class="relative">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <input type="number" name="children_count" min="0" max="20" value="{{ old('children_count', 0) }}"
                                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors pl-10">
                                    </div>
                                </div>

                                <div class="md:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                                    <textarea name="notes" rows="2" placeholder="Special requests, allergies, etc."
                                              class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors">{{ old('notes') }}</textarea>
                                </div>
                            </div>

                            {{-- Totals Bar --}}
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                                <div class="flex items-center gap-6 text-sm text-gray-600">
                                    <div>Total: <span class="font-bold text-gray-900 text-lg ml-1" id="total_text">0.00</span></div>
                                    <div class="h-4 w-px bg-gray-300"></div>
                                    <div>Paid: <span class="font-bold text-green-600 text-lg ml-1" id="paid_text">0.00</span></div>
                                    <div class="h-4 w-px bg-gray-300"></div>
                                    <div>Remaining: <span class="font-bold text-red-600 text-lg ml-1" id="remaining_text">0.00</span></div>
                                </div>

                                <div id="card_actions" class="hidden">
                                    <button type="button" id="openCardModal" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 shadow-sm transition-all">
                                        Enter Payment Details
                                    </button>
                                </div>
                            </div>

                            {{-- Room Selection --}}
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Available Rooms (Grouped by Type)</h4>
                                <div class="space-y-6">
                                    @foreach($availableRooms->groupBy(fn($r) => $r->roomType?->name ?? 'Unknown') as $typeName => $rooms)
                                        <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                                            <div class="px-4 py-2 bg-gray-100 border-b border-gray-200 font-semibold text-gray-700 text-sm">
                                                {{ $typeName }}
                                            </div>
                                            <table class="w-full text-left text-sm">
                                                <tbody class="divide-y divide-gray-200">
                                                    @foreach($rooms as $room)
                                                        @php $price = (float) ($room->roomType?->base_price_per_night ?? 0); @endphp
                                                        <tr class="hover:bg-white transition-colors cursor-pointer" onclick="document.getElementById('room_{{ $room->id }}').click()">
                                                            <td class="px-4 py-3 w-12 text-center">
                                                                <input type="checkbox" name="room_ids[]" id="room_{{ $room->id }}"
                                                                       value="{{ $room->id }}" data-price="{{ $price }}"
                                                                       class="room-check rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-4 h-4 cursor-pointer"
                                                                       {{ in_array($room->id, old('room_ids', [])) ? 'checked' : '' }}
                                                                       onclick="event.stopPropagation()">
                                                            </td>
                                                            <td class="px-4 py-3 font-medium text-gray-900">Room {{ $room->room_number }}</td>
                                                            <td class="px-4 py-3 text-right text-gray-600">${{ number_format($price, 2) }} / night</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endforeach
                                </div>
                                @error('room_ids') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                                <a href="{{ route('bookings.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                                    Cancel
                                </a>
                                <button type="submit" class="px-6 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-md transition-all">
                                    Confirm Booking
                                </button>
                            </div>
                        </form>

                        {{-- Card Payment Modal (Smaller Size) --}}
                        <div id="cardModal" class="fixed inset-0 hidden bg-black/50 z-50 flex items-center justify-center p-4 backdrop-blur-sm transition-all">
                            <div class="bg-white rounded-2xl p-5 w-full max-w-sm shadow-2xl transform transition-all scale-100">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-bold text-gray-900">Card Payment</h3>
                                    <button type="button" id="closeCardModal" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>

                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Card Number</label>
                                        <div class="relative">
                                            <input type="text" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 pl-10 py-2 text-sm" placeholder="0000 0000 0000 0000">
                                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Expiry</label>
                                            <input type="text" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 text-sm" placeholder="MM/YY">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">CVC</label>
                                            <input type="text" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 text-sm" placeholder="123">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount to Pay</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2 text-gray-500 font-bold">$</span>
                                            <input type="number" step="0.01" min="0" id="fake_pay_amount" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 pl-7 py-2 font-bold text-gray-900 text-sm" placeholder="0.00">
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5">
                                    <button type="button" id="confirmCardModal" class="w-full px-4 py-2.5 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 shadow-lg transition-all text-sm">
                                        Confirm Payment
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Scripts --}}
                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                const form = document.getElementById('bookingForm');
                                const checkInEl = document.getElementById('check_in_date');
                                const checkOutEl = document.getElementById('check_out_date');
                                const methodEl = document.getElementById('payment_method');
                                const paidInput = document.getElementById('paid_amount');
                                
                                // Text Elements
                                const totalText = document.getElementById('total_text');
                                const paidText = document.getElementById('paid_text');
                                const remainingText = document.getElementById('remaining_text');
                                
                                // UI Sections
                                const cardActions = document.getElementById('card_actions');
                                
                                // Modal Elements
                                const openCardModalBtn = document.getElementById('openCardModal');
                                const modal = document.getElementById('cardModal');
                                const closeModalBtn = document.getElementById('closeCardModal');
                                const doneModalBtn = document.getElementById('confirmCardModal');
                                const modalPayAmount = document.getElementById('fake_pay_amount');

                                function nightsBetween(checkIn, checkOut) {
                                    const inD = new Date(checkIn + 'T00:00:00');
                                    const outD = new Date(checkOut + 'T00:00:00');
                                    const diff = Math.round((outD - inD) / (1000 * 60 * 60 * 24));
                                    return diff > 0 ? diff : 1;
                                }

                                function calcTotal() {
                                    const nights = nightsBetween(checkInEl.value, checkOutEl.value);
                                    const checks = Array.from(document.querySelectorAll('.room-check'));
                                    const perNight = checks
                                        .filter(c => c.checked)
                                        .reduce((sum, c) => sum + parseFloat(c.dataset.price || '0'), 0);
                                    const total = perNight * nights;
                                    return Math.round(total * 100) / 100;
                                }

                                function refreshTotals() {
                                    const total = calcTotal();
                                    let paid = parseFloat(paidInput.value || '0');
                                    
                                    if (isNaN(paid) || paid < 0) paid = 0;
                                    if (paid > total) paid = total; // Cap paid amount at total

                                    // Update display
                                    paidInput.value = paid.toFixed(2);
                                    const remaining = Math.max(total - paid, 0);

                                    totalText.textContent = total.toFixed(2);
                                    paidText.textContent = paid.toFixed(2);
                                    remainingText.textContent = remaining.toFixed(2);
                                }

                                function togglePaymentUI() {
                                    const isCard = methodEl.value === 'card';
                                    cardActions.classList.toggle('hidden', !isCard);
                                    if (!isCard) {
                                        paidInput.value = '0';
                                        refreshTotals();
                                    }
                                }

                                // Event Listeners
                                document.querySelectorAll('.room-check').forEach(c => {
                                    c.addEventListener('change', refreshTotals);
                                });

                                methodEl.addEventListener('change', togglePaymentUI);

                                // Modal Logic
                                openCardModalBtn?.addEventListener('click', () => {
                                    modal.classList.remove('hidden');
                                    const currentTotal = calcTotal();
                                    // Default pay amount to full total if not set, else keep current paid value
                                    const currentPaid = parseFloat(paidInput.value);
                                    modalPayAmount.value = (currentPaid > 0 ? currentPaid : currentTotal).toFixed(2);
                                });

                                const closeModal = () => modal.classList.add('hidden');
                                closeModalBtn?.addEventListener('click', closeModal);

                                doneModalBtn?.addEventListener('click', () => {
                                    const total = calcTotal();
                                    let amt = parseFloat(modalPayAmount.value || '0');
                                    
                                    if (isNaN(amt) || amt < 0) amt = 0;
                                    if (amt > total) amt = total; // Validation

                                    paidInput.value = amt.toFixed(2);
                                    refreshTotals();
                                    closeModal();
                                });

                                // Form Submit Logic
                                form.addEventListener('submit', (e) => {
                                    refreshTotals();
                                    const isCard = methodEl.value === 'card';
                                    const paid = parseFloat(paidInput.value || '0');

                                    // If Card is selected but paid amount is 0, force modal open
                                    if (isCard && (!paid || paid <= 0)) {
                                        e.preventDefault();
                                        // Trigger modal click safely
                                        if(openCardModalBtn) openCardModalBtn.click();
                                        else alert("Please enter payment details for Card method.");
                                    }
                                });

                                // Initialize
                                togglePaymentUI();
                                refreshTotals();
                            });
                        </script>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>