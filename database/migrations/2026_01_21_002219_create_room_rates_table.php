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
       Schema::create('room_rates', function (Blueprint $table) {
    $table->id();
    $table->foreignId('room_id')->constrained()->cascadeOnDelete();
    $table->foreignId('time_slot_id')->constrained()->cascadeOnDelete();
    $table->foreignId('pricing_profile_id')->constrained()->cascadeOnDelete();
    $table->decimal('price', 8, 2);
    $table->timestamps();

    $table->unique(['room_id', 'time_slot_id', 'pricing_profile_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_rates');
    }
};
