<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Valeurs par defaut
        DB::table('settings')->insert([
            ['key' => 'conciergerie_email', 'value' => 'laconciergerie@groupe-aprosep.com', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'admin_email', 'value' => 'contact@privatecashless.com', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
