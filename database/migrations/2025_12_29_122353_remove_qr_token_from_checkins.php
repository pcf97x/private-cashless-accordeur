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
        $table->dropUnique(['qr_token']);
        $table->dropColumn('qr_token');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('checkins', function (Blueprint $table) {
        $table->string('qr_token')->unique()->nullable();
    });
    }
};
