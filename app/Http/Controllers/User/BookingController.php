<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $bookings = Booking::with('user')
            ->latest()
            ->paginate(10);

        return view('user.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking): View
    {
        
        return view('user.bookings.show', compact('booking'));
    }
}