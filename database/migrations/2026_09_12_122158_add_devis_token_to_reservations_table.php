<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('devis_token', 64)->nullable()->unique()->after('stripe_session_id');
            $table->text('devis_notes')->nullable()->after('devis_token');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['devis_token', 'devis_notes']);
        });
    }
};
