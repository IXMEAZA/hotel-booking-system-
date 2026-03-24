<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $isStaff =$user->role === 'admin';

        if ($isStaff) {
            
            $roomsCount = Room::count();
            $activeRoomsCount = Room::where('is_active', true)->count();

            $availableRoomsCount = Room::where('is_active', true)->where('status', 'available')->count();
            $occupiedRoomsCount  = Room::where('is_active', true)->where('status', 'occupied')->count();
            $maintenanceRoomsCount = Room::where('is_active', true)->where('status', 'maintenance')->count();

            $startOfMonth = Carbon::now()->startOfMonth();
            $bookingsThisMonth = Booking::where('created_at', '>=', $startOfMonth)->count();

            $pendingBookings = Booking::where('status', 'pending')->count();
            $confirmedBookings = Booking::where('status', 'confirmed')->count();
            $cancelledBookings = Booking::where('status', 'cancelled')->count();

            $latestBookings = Booking::with('rooms.roomType', 'user')
                ->latest()
                ->take(7)
                ->get();

            return view('dashboard.admin', compact(
                'roomsCount',
                'activeRoomsCount',
                'availableRoomsCount',
                'occupiedRoomsCount',
                'maintenanceRoomsCount',
                'bookingsThisMonth',
                'pendingBookings',
                'confirmedBookings',
                'cancelledBookings',
                'latestBookings'
            ));
        }

        // Customer dashboard 
        $myBookings = Booking::with('rooms.roomType')
            ->where('user_id', $user->id)
            ->latest()
            ->take(7)
            ->get();

        $myPending = Booking::where('user_id', $user->id)->where('status', 'pending')->count();
        $myConfirmed = Booking::where('user_id', $user->id)->where('status', 'confirmed')->count();
        $myCancelled = Booking::where('user_id', $user->id)->where('status', 'cancelled')->count();

        return view('dashboard.customer', compact(
            'myBookings',
            'myPending',
            'myConfirmed',
            'myCancelled'
        ));
    }
}