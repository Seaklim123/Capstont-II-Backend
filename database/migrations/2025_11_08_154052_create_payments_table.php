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
        Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_information_id')
              ->constrained('order_informations')
              ->onDelete('cascade');
        $table->string('paypal_payment_id')->nullable();
        $table->string('payer_id')->nullable();
        $table->string('payer_email')->nullable();
        $table->double('amount')->default(0);
        $table->string('currency', 10)->default('USD');
        $table->enum('status', ['created', 'approved', 'failed'])->default('created');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
