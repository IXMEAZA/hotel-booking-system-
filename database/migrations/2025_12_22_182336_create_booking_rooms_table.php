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
        Schema::create('booking_rooms', function (Blueprint $table) {
            $table->id();
             $table->foreignId('booking_id')
            ->constrained('bookings')
            ->cascadeOnDelete();

        $table->foreignId('room_id')
            ->constrained('rooms')
            ->restrictOnDelete()
            ->cascadeOnUpdate();

        // نحفظ السعر وقت الحجز حتى لو السعر تغير لاحقاً
        $table->integer('price_per_night');
        $table->integer('nights')->nullable();
        $table->integer('line_total')->nullable();

        $table->timestamps();

        $table->unique(['booking_id', 'room_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_rooms');
    }
};
