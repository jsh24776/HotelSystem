<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
             $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('reservation_id')->unique();
            $table->string('guest_name');
            $table->string('room_number');
            $table->string('room_type');
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('number_of_guests');
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'checked-in', 'checked-out', 'cancelled'])->default('confirmed');
            $table->text('special_requests')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index(['check_in', 'check_out']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
