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
        Schema::table('planning_events', function (Blueprint $table) {
            $table->foreignId('room_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->foreignId('time_slot_id')->nullable()->after('room_id')->constrained()->nullOnDelete();
            $table->string('visibility')->default('public')->after('color'); // public, private
        });
    }

    public function down(): void
    {
        Schema::table('planning_events', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
            $table->dropForeign(['time_slot_id']);
            $table->dropColumn(['room_id', 'time_slot_id', 'visibility']);
        });
    }
};
