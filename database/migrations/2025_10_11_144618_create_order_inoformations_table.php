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
        Schema::create('order_inoformations', function (Blueprint $table) {
            $table->id();
            $table->integer('numberOrder');
            $table->decimal('totalPrice')->default(0);
            $table->double('discount')->default(0);
            $table->enum('status', ['starting', 'done']);
            $table->enum('payment', ['card', 'cash']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_inoformations');
    }
};
