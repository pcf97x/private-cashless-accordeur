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
   Schema::create('reservations', function (Blueprint $table) {
    $table->id();

    $table->foreignId('room_id')->constrained();
    $table->foreignId('time_slot_id')->constrained();
    $table->foreignId('pricing_profile_id')->constrained();

    $table->date('date');
    $table->timestamp('start_at')->nullable();
    $table->timestamp('end_at')->nullable();

    // 🔴 INFOS CLIENT
    $table->string('name');
    $table->string('email');
    $table->string('phone');

    $table->decimal('price', 8, 2);
    $table->string('status')->default('PENDING');

    $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
