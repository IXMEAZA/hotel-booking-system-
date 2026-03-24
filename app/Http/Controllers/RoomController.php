<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;

class RoomController extends Controller
{
   public function index(Request $request)
{
    $query = Room::query()->with('roomType');

    
    if ($request->filled('status')) {
        $query->status($request->status);
    }

    
    if ($request->filled('room_type_id')) {
        $query->roomType((int) $request->room_type_id); 
    }

   
    if ($request->filled('floor')) {
        $query->floor((int) $request->floor); 
    }

    $rooms = $query->latest()->paginate(10)->withQueryString();

    
    $roomTypes = RoomType::orderBy('name')->get(['id', 'name']);
    $floors = Room::query()
        ->select('floor')
        ->whereNotNull('floor')
        ->distinct()
        ->orderBy('floor')
        ->pluck('floor');

    return view('rooms.index', compact('rooms', 'roomTypes', 'floors'));
}

    public function create()
    {
        $roomTypes = RoomType::where('is_active', true)->orderBy('name')->get();
        return view('rooms.create', compact('roomTypes'));
    }

    public function store(StoreRoomRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        Room::create($data);

        return redirect()->route('rooms.index')->with('success', 'Room created successfully.');
    }

    public function edit(Room $room)
    {
        $roomTypes = RoomType::where('is_active', true)->orderBy('name')->get();
        return view('rooms.edit', compact('room', 'roomTypes'));
    }

    public function update(UpdateRoomRequest $request, Room $room)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $room->update($data);

        return redirect()->route('rooms.index')->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully.');
    }
}
