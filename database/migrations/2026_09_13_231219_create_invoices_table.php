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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->string('client_name');
            $table->string('client_email')->nullable();
            $table->string('client_phone')->nullable();
            $table->text('client_address')->nullable();
            $table->string('programme')->nullable();
            $table->string('chorus_reference')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('devis_sent_at')->nullable();
            $table->decimal('deposit_amount', 10, 2)->default(0);
            $table->timestamp('deposit_received_at')->nullable();
            $table->timestamp('invoice_sent_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('total_ht', 10, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('invoice_reservation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reservation_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_reservation');
        Schema::dropIfExists('invoices');
    }
};
