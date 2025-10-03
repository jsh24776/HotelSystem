<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'guest_name' => 'required|string|max:255',
            'room_number' => 'required|string|max:10',
            'room_type' => 'required|string|max:50',
            'check_in' => 'required|date|after:today',
            'check_out' => 'required|date|after:check_in',
            'number_of_guests' => 'required|integer|min:1|max:10',
            'total_amount' => 'required|numeric|min:0',
            'special_requests' => 'nullable|string',
        ];
    }
}