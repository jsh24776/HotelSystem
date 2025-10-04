<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        try {
            $rooms = Room::all();
            return response()->json($rooms);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch rooms',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $room = Room::findOrFail($id);
        $room->status = $request->status;
        $room->save();

        return response()->json([
            'success' => true,
            'message' => 'Room status updated successfully!',
            'room' => $room,
        ]);
    }
}