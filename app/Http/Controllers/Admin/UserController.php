<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
           $users = User::active()
                ->where('role', '!=', 'admin')
                ->get();
        return view('admin.users.index', compact('users'));
    }

    public function create() {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string',
            'password' => 'required|min:8',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        
        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit($id) {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user')); 
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string',
            'status' => 'required|in:Check-in,Check-out',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
         $user->update(['is_archived' => true]);

    return redirect()->route('admin.users.index')
        ->with('success', 'User archived successfully.');
    }

    public function restore($id)
{
    $user = User::withTrashed()->findOrFail($id);
    $user->update(['is_archived' => false]);

    return redirect()->route('admin.users.index')
        ->with('success', 'User restored successfully.');
}



 public function show($id)
    {
        $user = User::with(['reservations.room'])
            ->findOrFail($id);
            
        $reservations = $user->reservations()
            ->with('room')
            ->orderBy('check_in_date', 'desc')
            ->get();

        return view('admin.users.show', compact('user', 'reservations'));
    }

    public function getStayHistory($id)
    {
        try {
            $user = User::findOrFail($id);
            $reservations = $user->reservations()
                ->with('room')
                ->orderBy('check_in_date', 'desc')
                ->get()
                ->map(function ($reservation) {
                    return [
                        'id' => $reservation->id,
                        'reservation_id' => 'RSV-' . str_pad($reservation->id, 3, '0', STR_PAD_LEFT),
                        'room_number' => $reservation->room ? $reservation->room->room_number : 'N/A',
                        'room_type' => $reservation->room ? $reservation->room->room_type : 'N/A',
                        'check_in_date' => $reservation->check_in_date,
                        'check_out_date' => $reservation->check_out_date,
                        'status' => $reservation->status,
                        'total_amount' => $reservation->total_amount,
                        'guest_count' => $reservation->guest_count,
                        'payment_method' => $reservation->payment_method,
                        'stay_duration' => \Carbon\Carbon::parse($reservation->check_in_date)
                            ->diffInDays(\Carbon\Carbon::parse($reservation->check_out_date)),
                        'created_at' => $reservation->created_at,
                    ];
                });

            $stats = [
                'total_stays' => $reservations->count(),
                'total_nights' => $reservations->sum('stay_duration'),
                'total_spent' => $reservations->sum('total_amount'),
                'average_stay' => $reservations->count() > 0 ? round($reservations->sum('stay_duration') / $reservations->count(), 1) : 0,
                'last_visit' => $reservations->first() ? $reservations->first()['check_out_date'] : null,
            ];

            return response()->json([
                'reservations' => $reservations,
                'stats' => $stats,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch stay history',
                'message' => $e->getMessage()
            ], 500);
        }
    }

}