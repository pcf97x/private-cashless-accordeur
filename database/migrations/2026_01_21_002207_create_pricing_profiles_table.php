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
      Schema::create('pricing_profiles', function (Blueprint $table) {
    $table->id();
    $table->string('code')->unique(); // ABONNE, ADHERENT, NON_ADHERENT
    $table->string('label');
    $table->boolean('active')->default(true);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_profiles');
    }
};
