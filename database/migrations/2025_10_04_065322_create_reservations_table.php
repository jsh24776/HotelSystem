<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
          $table->id();
    $table->foreignId('guest_id')->nullable()->constrained()->cascadeOnDelete();
    $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete(); 
    $table->foreignId('room_id')->constrained()->cascadeOnDelete();

    $table->string('guest_name')->nullable();
    $table->string('email')->nullable();
    $table->string('phone')->nullable();
    $table->integer('guest_count')->default(1);
    $table->string('payment_method')->nullable();
    
    $table->date('check_in_date');
    $table->date('check_out_date');
    $table->decimal('total_amount', 10, 2);
    $table->enum('status', ['Pending', 'Confirmed', 'Cancelled'])->default('Pending');
    $table->timestamps();
        });
    }

 
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
