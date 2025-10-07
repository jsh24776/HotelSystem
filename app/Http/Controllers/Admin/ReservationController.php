<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Models\Guest;
    use App\Models\Room;
    use App\Models\Reservation;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str; 
    use Illuminate\Support\Facades\Hash;

    class ReservationController extends Controller
    {
        public function index()
        {
            $rooms = Room::where('status', 'Available')->get();
        $guests = Guest::all();
        $reservations = Reservation::with('room')
            ->orderBy('created_at', 'desc')
            ->get();
        
    
        \Log::info('Rooms count: ' . $rooms->count());
        \Log::info('Reservations count: ' . $reservations->count());
        
        return view('admin.bookings.index', compact('rooms', 'reservations', 'guests'));

        }

        public function create()
    {
        $rooms = Room::where('status', 'Available')->get();
        $guests = Guest::all();
        return view('admin.bookings.create', compact('rooms', 'guests'));
    }
    public function getReservationsData()
    {
            try {
                $reservations = Reservation::with('room')
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->map(function ($reservation) {
                        return [
                            'id' => $reservation->id,
                            'reservation_id' => 'RSV-' . str_pad($reservation->id, 3, '0', STR_PAD_LEFT),
                            'guest_name' => $reservation->guest_name,
                            'room_number' => $reservation->room ? $reservation->room->room_number : 'N/A',
                            'room_type' => $reservation->room ? $reservation->room->room_type : 'N/A',
                            'check_in_date' => $reservation->check_in_date,
                            'check_out_date' => $reservation->check_out_date,
                            'status' => $reservation->status,
                            'total_amount' => $reservation->total_amount,
                            'email' => $reservation->email,
                            'phone' => $reservation->phone,
                            'guest_count' => $reservation->guest_count,
                            'payment_method' => $reservation->payment_method,
                        ];
                    });

                return response()->json($reservations);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Failed to fetch reservations',
                    'message' => $e->getMessage()
                ], 500);
            }
        }
        public function store(Request $request)
        {
            $validated = $request->validate([
            'guest_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'room_id' => 'required|integer|exists:rooms,id',
            'guest_count' => 'required|integer|min:1|max:10',
            'check_in_date' => 'required|date|after:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:Cash,PayPal',
        ], [
            'check_in_date.after' => 'Check-in date must be in the future',
            'check_out_date.after' => 'Check-out date must be after check-in date',
        ]);

            \Log::info('Creating reservation with data:', $validated);


            $user = \App\Models\User::where('email', $validated['email'])->first();

            if (!$user) {
            $user = \App\Models\User::create([
                'name' => $validated['guest_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => bcrypt(Str::random(12)),  
                'role' => 'user',
                'status' => 'check-in',
            ]);

            \Log::info('New user created:', ['user_id' => $user->id, 'email' => $user->email]);
        }


            $reservation = Reservation::create([
            'guest_name' => $validated['guest_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'room_id' => $validated['room_id'],
            'guest_count' => $validated['guest_count'],
            'check_in_date' => $validated['check_in_date'],
            'check_out_date' => $validated['check_out_date'],
            'total_amount' => $validated['total_amount'],
            'payment_method' => $validated['payment_method'],
            'status' => 'check-in',
            'user_id' => $user->id
            ]);
            Room::where('id', $request->room_id)->update(['status' => 'Occupied']);

            return response()->json([
            'success' => true,
            'message' => 'Reservation created successfully!',
            'reservation' => $reservation,
            'user_created' => !$user->wasRecentlyCreated,
            ]);
        }
    }
