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
       Schema::table('checkins', function (Blueprint $table) {
        $table->string('weez_ticket_code')->nullable()->unique();
        $table->string('weez_event_id')->nullable()->index();
        $table->string('weez_participant_id')->nullable()->index();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('checkins', function (Blueprint $table) {
             $table->dropColumn([
            'weez_ticket_code',
            'weez_event_id',
            'weez_participant_id',
        ]);
        });
    }
};
