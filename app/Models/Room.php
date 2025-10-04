<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class Room extends Model
{
     use HasFactory;

    protected $fillable = [
        'room_number',
        'room_type',
        'capacity',
        'price_per_night',
        'status',
    ];
}
