<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Bookings Management
                </h2>
                <p class="text-sm text-gray-500">Manage hotel reservations and guests</p>
            </div>

            <a href="{{ route('bookings.create') }}"
               class="inline-flex items-center justify-center px-6 py-2 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition-all w-full sm:w-auto">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Booking
            </a>
        </div>
    </x-slot>

    @php
        $user = auth()->user();
        $isStaff = in_array($user->role, ['admin', 'receptionist'], true);

        // ✅ نفس الفكرة: وزن الطفل (بدّلها لو تبي)
        $CHILD_WEIGHT = 0.5;
    @endphp

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

            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-sm flex items-center justify-between">
                    <div class="flex items-center text-red-800">
                        <svg class="w-6 h-6 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-red-600 hover:text-red-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">

                {{-- Header & Filter Section --}}
                <div class="p-6 border-b border-gray-100 bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                    {{-- Title & Count --}}
                    <div class="flex items-center gap-3">
                        <div class="bg-indigo-50 p-2 rounded-lg text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">All Bookings</h3>
                            <span class="text-xs text-gray-500">{{ $bookings->total() }} records found</span>
                        </div>
                    </div>

                    {{-- Filter Dropdown (Status Only) --}}
                    <div class="w-full sm:w-auto relative group">
                        <form action="{{ route('bookings.index') }}" method="GET">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                            </div>

                            <select name="status" onchange="this.form.submit()"
                                    class="appearance-none block w-full sm:w-64 pl-10 pr-4 py-2 text-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm bg-gray-50 hover:bg-white cursor-pointer transition-all">
                                <option value="">Status (All)</option>
                                <option value="pending"   {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </form>
                    </div>
                </div> {{-- ✅ كان ناقص عندك إغلاق هذا الـ div --}}

                {{-- Table Section --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                <th class="py-4 px-6">Booking Code</th>
                                <th class="py-4 px-6">Guest Info</th>
                                <th class="py-4 px-6">Date Range</th>
                                <th class="py-4 px-6 text-center">Status</th>
                                <th class="py-4 px-6 text-right">Total</th>
                                <th class="py-4 px-6 text-right">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($bookings as $booking)
                                @php
                                    $isOwner = $booking->user_id === $user->id;
                                    $canEdit = $isStaff || ($isOwner && $booking->status === 'pending');
                                    $canCancel = ($isStaff && $booking->status !== 'cancelled') || ($isOwner && $booking->status === 'pending');

                                    // ✅ 1) مجموع الغرف (كما هو عندك)
                                    $baseTotal = $booking->rooms->sum(fn($room) => (float)($room->pivot->line_total ?? 0));

                                    // ✅ 2) عامل الأشخاص: adults + children*0.5
                                    $adults = (float) ($booking->adults_count ?? 1);
                                    $children = (float) ($booking->children_count ?? 0);

                                    $adults = $adults > 0 ? $adults : 1;
                                    $children = $children > 0 ? $children : 0;

                                    $guestFactor = $adults + ($children * $CHILD_WEIGHT);
                                    if ($guestFactor <= 0) $guestFactor = 1;

                                    // ✅ 3) السعر النهائي للعرض
                                    $totalPrice = $baseTotal * $guestFactor;

                                    $statusClasses = match($booking->status) {
                                        'confirmed' => 'bg-green-100 text-green-700 border border-green-200',
                                        'pending' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                        'cancelled' => 'bg-red-100 text-red-700 border border-red-200',
                                        'checked_in' => 'bg-blue-100 text-blue-700 border border-blue-200',
                                        'checked_out' => 'bg-gray-100 text-gray-600 border border-gray-200',
                                        default => 'bg-gray-50 text-gray-600',
                                    };

                                    $dotColor = match($booking->status) {
                                        'confirmed' => 'text-green-600',
                                        'pending' => 'text-yellow-600',
                                        'cancelled' => 'text-red-600',
                                        'checked_in' => 'text-blue-600',
                                        'checked_out' => 'text-gray-500',
                                        default => 'text-gray-400',
                                    };
                                @endphp

                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                            </div>
                                            <div>
                                                <span class="block text-sm font-bold text-gray-900">{{ $booking->booking_code }}</span>
                                                <span class="text-xs text-gray-400">{{ $booking->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="py-4 px-6">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</span>
                                            <span class="text-xs text-gray-500">{{ $booking->user->email }}</span>
                                        </div>
                                    </td>

                                    <td class="py-4 px-6">
                                        <div class="flex flex-col text-sm">
                                            <span class="text-gray-700 font-medium">{{ $booking->check_in_date?->format('M d, Y') }}</span>
                                            <span class="text-xs text-gray-400">to {{ $booking->check_out_date?->format('M d, Y') }}</span>
                                        </div>
                                    </td>

                                    <td class="py-4 px-6 text-center">
                                        <span class="inline-flex items-center gap-x-1.5 rounded-full px-3 py-1 text-xs font-bold {{ $statusClasses }}">
                                            <svg class="h-1.5 w-1.5 {{ $dotColor }} fill-current" viewBox="0 0 6 6" aria-hidden="true">
                                              <circle cx="3" cy="3" r="3" />
                                            </svg>
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>

                                    <td class="py-4 px-6 text-right">
                                        <span class="text-sm font-bold text-gray-900 font-mono">
                                            ${{ number_format($totalPrice, 2) }}
                                        </span>
                                        <div class="text-xs text-gray-400">
                                            {{ $booking->rooms->count() }} Room(s)
                                        </div>
                                    </td>

                                    <td class="py-4 px-6 text-right">
                                        <div class="flex items-center justify-end gap-2">

                                            {{-- Details --}}
                                            <a href="{{ route('bookings.show', $booking) }}"
                                               class="text-gray-500 hover:text-indigo-600 bg-gray-50 hover:bg-indigo-50 border border-gray-200 hover:border-indigo-200 p-2 rounded-lg transition-all"
                                               title="View Details">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>

                                            {{-- Edit --}}
                                            @if($canEdit)
                                                <a href="{{ route('bookings.edit', $booking) }}"
                                                   class="text-gray-500 hover:text-amber-600 bg-gray-50 hover:bg-amber-50 border border-gray-200 hover:border-amber-200 p-2 rounded-lg transition-all"
                                                   title="Edit Booking">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                            @endif

                                            {{-- Confirm --}}
                                            @if($isStaff && $booking->status === 'pending')
                                                <form method="POST" action="{{ route('bookings.confirm', $booking) }}" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                            class="text-gray-500 hover:text-green-600 bg-gray-50 hover:bg-green-50 border border-gray-200 hover:border-green-200 p-2 rounded-lg transition-all"
                                                            title="Confirm Booking">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- Cancel/Delete --}}
                                            @if($canCancel)
                                                <form method="POST" action="{{ route('bookings.destroy', $booking) }}" class="inline" onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-gray-500 hover:text-red-600 bg-gray-50 hover:bg-red-50 border border-gray-200 hover:border-red-200 p-2 rounded-lg transition-all"
                                                            title="Cancel Booking">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <div class="bg-gray-100 p-4 rounded-full mb-3">
                                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <p class="text-lg font-medium text-gray-500">No bookings found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($bookings->hasPages())
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                        {{ $bookings->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
