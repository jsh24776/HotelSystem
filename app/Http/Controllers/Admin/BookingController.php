<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
     
        $rooms = Room::where('status', 'Available')->get();
        return view('admin.bookings.index', compact('rooms'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email',
            'phone'         => 'nullable|string|max:20',
            'room_id'       => 'required|exists:rooms,id',
            'check_in_date' => 'required|date',
            'check_out_date'=> 'required|date|after_or_equal:check_in_date',
        ]);


        $user = User::firstOrCreate(
            ['email' => $request->email],
            [
                'fullname' => $request->name,
                'username' => strtolower(str_replace(' ', '', $request->name)),
                'password' => bcrypt('password'), 
                'role'     => 'user',
            ]
        );

       
        Reservation::create([
            'user_id'        => $user->id,
            'room_id'        => $request->room_id,
            'check_in_date'  => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'total_amount'   => 0, 
            'status'         => 'Confirmed',
        ]);

        Room::where('id', $request->room_id)->update(['status' => 'Occupied']);

        return redirect()->back()->with('success', 'Booking successfully created.');
    }
}
