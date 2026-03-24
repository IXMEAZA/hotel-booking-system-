<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
    $user = auth()->user();
    $isStaff = $user->role === 'admin';
    $query = Booking::query()->with(['rooms.roomType', 'user']); 

    if (!$isStaff) {
        $query->where('user_id', $user->id);
    }

    if ($request->filled('status')) {
    $query->where('status', $request->query('status'));
    }

    $bookings = $query->latest()->paginate(10)->withQueryString();

    return view('bookings.index', compact('bookings'));
    }

  public function create(Request $request)
{
    $availableRooms = collect();

    if ($request->filled(['check_in_date','check_out_date'])) {
        $request->validate([
            'check_in_date' => ['required','date'],
            'check_out_date' => ['required','date','after:check_in_date'],
        ]);

        $availableRooms = Room::with('roomType')
            ->availableBetween($request->check_in_date, $request->check_out_date)
            ->orderBy('room_type_id')
            ->orderBy('room_number')
            ->get();
    }

    return view('bookings.create', compact('availableRooms'));
}

 public function store(StoreBookingRequest $request)
{
    $checkIn = $request->check_in_date;
    $checkOut = $request->check_out_date;

    $roomIds = $request->room_ids;

   
    $availableIds = Room::availableBetween($checkIn, $checkOut)->pluck('id')->all();

    foreach ($roomIds as $id) {
        if (!in_array((int)$id, $availableIds, true)) {
            return back()->withErrors(['room_ids' => 'One or more selected rooms are not available.'])->withInput();
        }
    }

    
    $nights = Carbon::parse($checkIn)->diffInDays(Carbon::parse($checkOut));
    if ($nights <= 0) $nights = 1;

    
    $code = $this->generateBookingCode();

    
    $rooms = Room::with('roomType')->whereIn('id', $roomIds)->get();

    $roomsPerNight = $rooms->sum(fn($r) => (float) ($r->roomType->base_price_per_night ?? 0));
    $roomTotal = $roomsPerNight * $nights;
    $total = $roomTotal;
    
    $paymentMethod = $request->payment_method ?? 'cash';
    $paid = (float) ($request->paid_amount ?? 0);

    if ($paid < 0) $paid = 0;
   

    
    if ($paymentMethod === 'cash') {
        $paid = 0;
    }

    $remaining = $total - $paid;

    
    $status = ($paymentMethod === 'card' && $paid > 0) ? 'confirmed' : 'pending';

    
    $booking = Booking::create([
        'booking_code' => $code,
        'user_id' => auth()->id(),

        'guest_name' => $request->guest_name,
        'guest_phone' => $request->guest_phone,
        'payment_method' => $paymentMethod,

        'total_amount' => $total,
        'paid_amount' => $paid,
        'remaining_amount' => $remaining,

        'check_in_date' => $checkIn,
        'check_out_date' => $checkOut,
        'adults_count' => $request->adults_count,
        'children_count' => $request->children_count ?? 0,
        'status' => $status,
        'notes' => $request->notes,

        'created_by' => auth()->id(),
        'modified_by' => auth()->id(),
    ]);

    
    foreach ($rooms as $room) {
        $price = (float) ($room->roomType->base_price_per_night ?? 0);

        $booking->rooms()->attach($room->id, [
            'price_per_night' => $price,
            'nights' => $nights,
            'line_total' => $price * $nights,
        ]);
    }

    return redirect()
        ->route('bookings.show', $booking)
        ->with('success', 'Booking created successfully.');
}


    public function show(Booking $booking)
    {
        $user = auth()->user();

       if ($user->role !== 'admin' && $booking->user_id !== $user->id) {
    abort(403);
}


        $booking->load(['rooms.roomType']);

        $nights = $booking->check_in_date->diffInDays($booking->check_out_date);
        $roomsPerNight = $booking->rooms->sum(fn($r) => (float)$r->pivot->price_per_night);
        $roomTotal = $nights * $roomsPerNight;

        $grandTotal = $roomTotal - (float)$booking->discount_amount + (float)$booking->tax_amount;

        return view('bookings.show', compact('booking','nights','roomTotal','grandTotal'));
    }

    public function edit(Booking $booking, Request $request)
    {
        $user = auth()->user();

     if ($user->role !== 'admin' && $booking->user_id !== $user->id) {
    abort(403);
}

        $roomTypes = RoomType::where('is_active', true)->orderBy('name')->get();
        $availableRooms = collect();

        $checkIn = $request->check_in_date ?? $booking->check_in_date->toDateString();
        $checkOut = $request->check_out_date ?? $booking->check_out_date->toDateString();
        $roomTypeId = $request->room_type_id ?? $booking->rooms->first()?->room_type_id;

        if ($checkIn && $checkOut && $roomTypeId) {
            $availableRooms = Room::with('roomType')
                ->where('room_type_id', $roomTypeId)
                ->availableBetween($checkIn, $checkOut, $booking->id)
                ->orderBy('room_number')
                ->get();
        }

        $selectedRoomIds = $booking->rooms->pluck('id')->all();

        return view('bookings.edit', compact('booking','roomTypes','availableRooms','selectedRoomIds','checkIn','checkOut','roomTypeId'));
    }

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        $user = auth()->user();

        if ($user->role !== 'admin' && $booking->user_id !== $user->id) {
    abort(403);
}

        $checkIn = $request->check_in_date;
        $checkOut = $request->check_out_date;
        $roomTypeId = (int) $request->room_type_id;
        $roomIds = $request->room_ids;

        $availableIds = Room::where('room_type_id', $roomTypeId)
            ->availableBetween($checkIn, $checkOut, $booking->id)
            ->pluck('id')
            ->all();

        foreach ($roomIds as $id) {
            if (!in_array((int)$id, $availableIds, true)) {
                return back()->withErrors([
                    'room_ids' => 'One or more selected rooms are no longer available for these dates.'
                ])->withInput();
            }
        }

        $nights = Carbon::parse($checkIn)->diffInDays(Carbon::parse($checkOut));

        $booking->update([
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'adults_count' => $request->adults_count,
            'children_count' => $request->children_count ?? 0,
            'notes' => $request->notes,
            'modified_by' => auth()->id(),
        ]);

       
        $booking->rooms()->detach();
        $rooms = Room::with('roomType')->whereIn('id', $roomIds)->get();

        foreach ($rooms as $room) {
            $price = (float) $room->roomType->base_price_per_night;
            $booking->rooms()->attach($room->id, [
                'price_per_night' => $price,
                'nights' => $nights,
                'line_total' => $price * $nights,
            ]);
        }

        return redirect()
            ->route('bookings.show', $booking)
            ->with('success', 'Booking updated successfully.');
    }

  public function destroy(Booking $booking)
{
    $user = auth()->user();
    $isAdmin = $user->role === 'admin';

   
    if (!$isAdmin && $booking->user_id !== $user->id) {
        abort(403);
    }

   
    if (!$isAdmin && $booking->status !== 'pending') {
        return back()->with('error', 'You can only cancel a pending booking.');
    }

    
    if ($booking->status === 'cancelled') {
        return back()->with('success', 'This booking is already cancelled.');
    }

    $booking->update([
        'status' => 'cancelled',
        'modified_by' => $user->id,
    ]);

    return back()->with('success', 'Booking cancelled successfully.');
}



    public function checkIn(Booking $booking)
    {
        $user = auth()->user();

       if ($user->role !== 'admin') {
    abort(403);
}

        $booking->update(['status' => 'checked_in', 'modified_by' => auth()->id()]);

        $booking->load('rooms');
        foreach ($booking->rooms as $room) {
            if ($room->status !== 'maintenance') {
                $room->update(['status' => 'occupied']);
            }
        }

        return back()->with('success', 'Checked-in successfully.');
    }

    public function checkOut(Booking $booking)
    {
        $user = auth()->user();

      if ($user->role !== 'admin') {
    abort(403);
}

        $booking->update(['status' => 'checked_out', 'modified_by' => auth()->id()]);

      $booking->load('rooms');
        foreach ($booking->rooms as $room) {
            if ($room->status !== 'maintenance') {
                $room->update(['status' => 'available']);
            }
        }

        return back()->with('success', 'Checked-out successfully.');
    }


    private function generateBookingCode(): string
    {
        do {
            $code = 'BK' . now()->format('ymd') . strtoupper(Str::random(4));
        } while (Booking::where('booking_code', $code)->exists());

        return $code;
    }



    public function confirm(Booking $booking)
{
    $user = auth()->user();
    $isStaff = $user->role === 'admin';
    if (!$isStaff) {
        abort(403);
    }

    if ($booking->status !== 'pending') {
        return back()->with('error', 'Only pending bookings can be confirmed.');
    }

    $booking->update([
        'status' => 'confirmed',
        'modified_by' => $user->id,
    ]);

    return back()->with('success', 'Booking confirmed successfully.');
} 
}


