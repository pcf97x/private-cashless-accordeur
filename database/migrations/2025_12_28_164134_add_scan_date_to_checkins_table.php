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
    if (!Schema::hasColumn('checkins', 'scan_date')) {
        $table->date('scan_date')->nullable()->index();
    }

    if (!Schema::hasColumn('checkins', 'entry_at')) {
        $table->timestamp('entry_at')->nullable();
    }

    if (!Schema::hasColumn('checkins', 'exit_at')) {
        $table->timestamp('exit_at')->nullable();
    }
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('checkins', function (Blueprint $table) {
            //
        });
    }
};
