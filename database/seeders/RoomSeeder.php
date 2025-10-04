<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
  
    public function run(): void
    {
    Room::create(['room_number' => '101', 'room_type' => 'Deluxe', 'capacity' => 2, 'price_per_night' => 2500, 'status' => 'Available']);
    Room::create(['room_number' => '102', 'room_type' => 'Suite', 'capacity' => 4, 'price_per_night' => 4000, 'status' => 'Available']);
    Room::create(['room_number' => '103', 'room_type' => 'Standard', 'capacity' => 2, 'price_per_night' => 1800, 'status' => 'Available']);
    }
}
