<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_name',
        'email',
        'phone',
        'room_id',
        'guest_count',
        'check_in_date',
        'check_out_date',
        'total_amount',
        'payment_method',
        'status',
        'user_id',
        'guest_id',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
      public function user()
    {
        return $this->belongsTo(User::class);
    }


     public function guest()
    {
        return $this->belongsTo(User::class, 'guest_id'); 
    }
}
