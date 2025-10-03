<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'guest_name' => 'sometimes|required|string|max:255',
            'room_number' => 'sometimes|required|string|max:10',
            'room_type' => 'sometimes|required|string|max:50',
            'check_in' => 'sometimes|required|date',
            'check_out' => 'sometimes|required|date|after:check_in',
            'number_of_guests' => 'sometimes|required|integer|min:1|max:10',
            'total_amount' => 'sometimes|required|numeric|min:0',
            'status' => 'sometimes|required|in:pending,confirmed,checked-in,checked-out,cancelled',
            'special_requests' => 'nullable|string',
        ];
    }
}