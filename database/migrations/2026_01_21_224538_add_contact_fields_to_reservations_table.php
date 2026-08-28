<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('reservations', 'name')) {
                $table->string('name')->after('end_at');
            }
            if (!Schema::hasColumn('reservations', 'email')) {
                $table->string('email')->after('name');
            }
            if (!Schema::hasColumn('reservations', 'phone')) {
                $table->string('phone')->after('email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['name', 'email', 'phone']);
        });
    }
};
