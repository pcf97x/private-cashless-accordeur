<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('checkins', function (Blueprint $table) {
            $table->dropUnique(['weez_ticket_code']);
            $table->index('weez_ticket_code');
        });
    }

    public function down(): void
    {
        Schema::table('checkins', function (Blueprint $table) {
            $table->dropIndex(['weez_ticket_code']);
            $table->unique('weez_ticket_code');
        });
    }
};
