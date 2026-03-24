<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreRoomTypeRequest;
use App\Http\Requests\UpdateRoomTypeRequest;

class RoomTypeController extends Controller
{
    public function index()
    {
        $query = RoomType::latest();

        
        if (auth()->user()->role !== 'admin') {
            $query->where('is_active', true);
        }

        $roomTypes = $query->paginate(12);

        return view('room_types.index', compact('roomTypes'));
    }

    public function show(RoomType $roomType)
    {
        
        if (auth()->user()->role !== 'admin' && !$roomType->is_active) {
            abort(404);
        }

        return view('room_types.show', compact('roomType'));
    }

    public function create()
    {
        return view('room_types.create');
    }

    public function store(StoreRoomTypeRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('room-types', 'public');
        }

        RoomType::create($data);

        return redirect()
            ->route('room-types.index')
            ->with('success', 'Room type created successfully.');
    }

    public function edit(RoomType $roomType)
    {
        return view('room_types.edit', compact('roomType'));
    }

    public function update(UpdateRoomTypeRequest $request, RoomType $roomType)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

       
        if ($request->hasFile('image')) {
            if ($roomType->image && Storage::disk('public')->exists($roomType->image)) {
                Storage::disk('public')->delete($roomType->image);
            }

            $data['image'] = $request->file('image')->store('room-types', 'public');
        }

        $roomType->update($data);

        return redirect()
            ->route('room-types.index')
            ->with('success', 'Room type updated successfully.');
    }

   public function destroy(RoomType $roomType)
{
    DB::transaction(function () use ($roomType) {
       
        $roomType->update([
            'is_active'   => false,
        ]);

       
        $roomType->rooms()->update([
            'is_active'   => false,
        ]);
    });

    return redirect()
        ->route('room-types.index')
        ->with('success', 'Room type disabled successfully.');
}
}
