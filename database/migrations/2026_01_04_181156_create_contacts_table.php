<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();

            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('company')->nullable();

            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable()->index();

            $table->string('source')->default('accordeur'); 
            // accordeur | weezevent | import | autre

            $table->boolean('email_optin')->default(false);
            $table->boolean('sms_optin')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
