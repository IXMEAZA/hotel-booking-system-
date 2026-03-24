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
            $table->string('booking_code')->unique(); // BK2025XXXX
        $table->foreignId('user_id') 
            ->constrained('users')
            ->restrictOnDelete()
            ->cascadeOnUpdate();
         $table->string('guest_name')->nullable();
         $table->string('guest_phone')->nullable();
         $table->enum('payment_method', ['cash', 'card'])->default('cash');
          $table->boolean('breakfast')->default(false);
            $table->boolean('lunch')->default(false);
            $table->boolean('dinner')->default(false);
        $table->decimal('total_amount', 10, 2)->default(0);
        $table->decimal('paid_amount', 10, 2)->default(0);
        $table->decimal('remaining_amount', 10, 2)->default(0);
        $table->decimal('meals_total', 10, 2)->default(0);
        $table->date('check_in_date');
        $table->date('check_out_date');

        $table->integer('adults_count')->default(1);
        $table->integer('children_count')->default(0);

        $table->string('status')->default('pending');
        // pending | confirmed | checked_in | checked_out | cancelled

        $table->integer('discount_amount')->default(0);
        

        $table->foreignId('created_by')
            ->constrained('users');
        $table->foreignId('modified_by')
            ->constrained('users');

        $table->text('notes')->nullable();

            $table->timestamps();
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
